<?php
namespace App\Http\Controllers;
use App\Models\Blueprint;
use App\Models\Question;
use App\Models\QuestionVersion;
use App\Models\Subject;
use App\Services\QuestionService;
use App\Support\AccessScope;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
class QuestionController extends Controller
{
    public function __construct(private readonly QuestionService $service) {}
    public function index(Request $request)
    {
        abort_unless($request->user()->can('question.view'), 403);
        $query = AccessScope::restrict(Question::with(['subject','latestVersion','author']), $request->user());
        if ($request->filled('status')) {
            $status = $request->validate(['status' => ['required', Rule::in(['draft', 'submitted', 'approved', 'rejected'])]])['status'];
            $query->whereHas('latestVersion', fn ($q) => $q->where('status', $status));
        }
        if ($request->filled('subject_id')) $query->where('subject_id', $request->integer('subject_id'));
        if ($request->filled('q')) $query->whereHas('versions', fn ($q) => $q->where('body', 'like', '%'.addcslashes($request->string('q')->toString(), '%_\\').'%'));
        return view('questions.index', ['questions'=>$query->latest()->paginate(15)->withQueryString(), 'subjects'=>$this->subjects($request)]);
    }
    public function create(Request $request)
    {
        abort_unless($request->user()->can('question.create'), 403);
        $blueprint = null;
        if ($request->filled('blueprint_id')) {
            $blueprint = Blueprint::findOrFail($request->integer('blueprint_id'));
            abort_unless(AccessScope::allows($request->user(), $blueprint->institution_id)
                && $request->user()->can('blueprint.view'), 403);
        }
        return view('questions.form', [
            'question'=>new Question, 'version'=>new QuestionVersion,
            'subjects'=>$this->subjects($request), 'blueprintContext'=>$blueprint,
        ]);
    }
    public function store(Request $request)
    {
        abort_unless($request->user()->can('question.create'), 403);
        $ids = $this->checkClassification($request);
        $blueprint = null;
        if ($request->filled('blueprint_id')) {
            $blueprint = Blueprint::findOrFail($request->integer('blueprint_id'));
            abort_unless($request->user()->can('blueprint.view')
                && AccessScope::allows($request->user(), $blueprint->institution_id)
                && (int) $ids['subject_id'] === (int) $blueprint->subject_id, 403);
        }
        $data = $this->service->validated($request);
        $q = DB::transaction(function () use ($ids, $data, $request) {
            $q = Question::create($ids + ['author_id'=>$request->user()->id]);
            $q->versions()->create($data + ['version_number'=>1,'status'=>'draft','created_by'=>$request->user()->id]);
            return $q;
        });
        if ($blueprint) {
            return redirect()->route('blueprints.questions.index', $blueprint)
                ->with('success', 'Draft question created. Submit it for independent review before adding it to this blueprint.');
        }
        return redirect()->route('questions.show', $q)->with('success', 'Draft question created.');
    }
    public function show(Request $request, Question $question)
    {
        $this->check($request, $question, 'question.view');
        return view('questions.show', ['question' => $question->load(['subject','topic','versions'])]);
    }
    public function edit(Request $request, Question $question)
    {
        $this->check($request, $question, 'question.create');
        $version = $question->latestVersion;
        abort_unless($version?->status === 'draft' && ($question->author_id === $request->user()->id || AccessScope::isGlobal($request->user())), 403);
        return view('questions.form', ['question'=>$question, 'version'=>$version, 'subjects'=>$this->subjects($request)]);
    }
    public function update(Request $request, Question $question)
    {
        $this->check($request, $question, 'question.create');
        abort_unless($question->author_id === $request->user()->id || AccessScope::isGlobal($request->user()), 403);
        $version = $question->latestVersion;
        abort_unless($version?->status === 'draft', 409, 'Submitted or reviewed versions cannot be modified.');
        $ids = $this->checkClassification($request);
        abort_unless($ids['institution_id'] === $question->institution_id, 422, 'Cannot move question to another institution.');
        if ($question->versions()->where('status','approved')->exists()) {
            abort_unless($question->subject_id === $ids['subject_id'] && $question->topic_id === $ids['topic_id'], 422, 'An approved question cannot change its academic classification.');
        }
        $data = $this->service->validated($request);
        DB::transaction(function () use ($question, $version, $ids, $data) {
            $question->update($ids);
            $version->update($data);
        });
        return redirect()->route('questions.show',$question)->with('success','Draft saved.');
    }
    public function submit(Request $request, Question $question)
    {
        $this->check($request, $question, 'question.create');
        abort_unless($question->author_id === $request->user()->id || AccessScope::isGlobal($request->user()), 403);
        $version = $question->latestVersion;
        abort_unless($version?->status === 'draft', 409);
        $version->update(['status'=>'submitted']);
        return back()->with('success', 'Submitted for independent review.');
    }
    public function review(Request $request, Question $question)
    {
        $this->check($request, $question, 'question.review');
        $data = $request->validate([
            'decision' => ['required',Rule::in(['approved','rejected'])],
            'review_note' => ['nullable','string','max:3000'],
        ]);
        if ($data['decision'] === 'rejected' && empty(trim($data['review_note'] ?? ''))) {
            return back()->withErrors(['review_note'=>'A rejection reason is required.']);
        }
        DB::transaction(function () use ($question, $request, $data) {
            $version = $question->versions()->lockForUpdate()->orderByDesc('version_number')->firstOrFail();
            abort_unless($version->status === 'submitted', 409);
            abort_if($version->created_by === $request->user()->id || $question->author_id === $request->user()->id, 403, 'Authors cannot review their own questions.');
            $version->update(['status'=>$data['decision'],'review_note'=>$data['review_note'] ?? null,'reviewed_by'=>$request->user()->id,'reviewed_at'=>now()]);
        });
        return back()->with('success', 'Review recorded.');
    }
    public function revise(Request $request, Question $question)
    {
        $this->check($request, $question, 'question.create');
        abort_unless($question->author_id === $request->user()->id || AccessScope::isGlobal($request->user()), 403);
        $version = DB::transaction(function () use ($question,$request) {
            $latest = $question->versions()->lockForUpdate()->orderByDesc('version_number')->firstOrFail();
            abort_if(in_array($latest->status,['draft','submitted'],true), 409, 'Finish the existing version first.');
            return $question->versions()->create([
                'version_number'=>$latest->version_number+1, 'type'=>$latest->type,'difficulty'=>$latest->difficulty,
                'body'=>$latest->body,'options'=>$latest->options,'answer_text'=>$latest->answer_text,
                'explanation'=>$latest->explanation,'marks'=>$latest->marks,
                // Preserve the full creative-question content when starting a revision.
                'format'=>$latest->format,'stimulus'=>$latest->stimulus,
                'creative_parts'=>$latest->creative_parts,
                'status'=>'draft','created_by'=>$request->user()->id,
            ]);
        });
        return redirect()->route('questions.edit',$question)->with('success','New editable version '.$version->version_number.' created.');
    }
    private function checkClassification(Request $request): array
    {
        $data = $request->validate(['subject_id'=>['required','integer','exists:subjects,id'], 'topic_id'=>['nullable','integer','exists:topics,id']]);
        $subject = Subject::findOrFail($data['subject_id']);
        abort_unless(AccessScope::allows($request->user(), $subject->institution_id), 403);
        if (isset($data['topic_id'])) abort_unless($subject->topics()->whereKey($data['topic_id'])->exists(), 422, 'Topic does not belong to the subject.');
        return ['institution_id'=>$subject->institution_id,'subject_id'=>$subject->id,'topic_id'=>$data['topic_id'] ?? null];
    }
    private function check(Request $request, Question $question, string $permission): void
    {
        abort_unless($request->user()->can($permission) && AccessScope::allows($request->user(),$question->institution_id), 403);
    }
    private function subjects(Request $request)
    {
        return AccessScope::restrict(Subject::query(), $request->user())->with('topics')->orderBy('name')->get();
    }
}

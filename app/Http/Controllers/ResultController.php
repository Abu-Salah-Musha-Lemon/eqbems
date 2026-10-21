<?php
namespace App\Http\Controllers;
use App\Models\Exam;
use App\Models\Result;
use App\Models\ResultRevision;
use App\Models\User;
use App\Services\GradeService;
use App\Support\AccessScope;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
class ResultController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()->can('result.view')) {
            $results = Result::with(['exam','student'])->whereHas('exam', function ($q) use ($request) {
                AccessScope::restrict($q, $request->user());
            })->latest()->paginate(20);
        } else {
            $results = Result::with('exam')->where('student_id',$request->user()->id)->where('status','published')->latest()->paginate(20);
        }
        return view('results.index',compact('results'));
    }
    public function create(Request $request)
    {
        abort_unless($request->user()->can('result.mark'),403);
        return view('results.form',[
            'exams'=>AccessScope::restrict(Exam::query(),$request->user())->where('status','issued')->latest()->get(),
            'students'=>AccessScope::restrict(User::role('Student'),$request->user())->orderBy('name')->get(),
            'result'=>new Result,
        ]);
    }
    public function store(Request $request)
    {
        abort_unless($request->user()->can('result.mark'),403);
        $data = $request->validate([
            'exam_id'=>['required','integer','exists:exams,id'],
            'student_id'=>['required','integer','exists:users,id'],
            'score'=>['required','numeric','min:0','decimal:0,2'],
        ]);
        $exam = Exam::findOrFail($data['exam_id']);
        $student = User::findOrFail($data['student_id']);
        abort_unless(AccessScope::allows($request->user(),$exam->institution_id) && $student->institution_id === $exam->institution_id && $student->hasRole('Student'),403);
        abort_unless($exam->status === 'issued',409,'Only issued exams can be marked.');
        $score = $this->validateScore((string)$data['score'],(string)$exam->total_marks);
        $result = Result::firstOrCreate(['exam_id'=>$exam->id,'student_id'=>$student->id],[
            'score'=>$score,'grade'=>GradeService::grade($score,(string)$exam->total_marks),
            'marked_by'=>$request->user()->id,'status'=>'draft',
        ]);
        if (! $result->wasRecentlyCreated) return back()->withErrors(['student_id'=>'Marks already exist for this student and exam. Edit the existing result.']);
        return redirect()->route('results.show',$result)->with('success','Draft marks recorded.');
    }
    public function show(Request $request, Result $result)
    {
        $this->check($request,$result);
        return view('results.show',['result'=>$result->load(['exam','student']), 'revisions'=>($request->user()->can('result.view') ? ResultRevision::where('result_id',$result->id)->latest()->get() : collect())]);
    }
    public function update(Request $request, Result $result)
    {
        abort_unless($request->user()->can('result.mark') && AccessScope::allows($request->user(),$result->exam->institution_id),403);
        $data = $request->validate([
            'score'=>['required','numeric','min:0','decimal:0,2'],
            'reason'=>[$result->status === 'published' ? 'required' : 'nullable','string','max:3000'],
        ]);
        $score = $this->validateScore((string)$data['score'],(string)$result->exam->total_marks);
        DB::transaction(function () use ($result,$data,$score,$request) {
            $record = Result::whereKey($result->id)->lockForUpdate()->firstOrFail();
            $oldScore = (string)$record->score;
            $newGrade = GradeService::grade($score,(string)$record->exam->total_marks);
            if (GradeService::cents($oldScore) !== GradeService::cents($score)) {
                ResultRevision::create([
                    'result_id'=>$record->id,'previous_score'=>$oldScore,'new_score'=>$score,
                    'previous_grade'=>$record->grade,'new_grade'=>$newGrade,
                    'reason'=>$data['reason'] ?? 'Draft marks adjusted','changed_by'=>$request->user()->id,
                ]);
            }
            $record->update(['score'=>$score,'grade'=>$newGrade,'marked_by'=>$request->user()->id]);
        });
        return back()->with('success','Marks updated with audit history.');
    }
    public function publish(Request $request, Result $result)
    {
        abort_unless($request->user()->can('result.publish') && AccessScope::allows($request->user(),$result->exam->institution_id),403);
        DB::transaction(function () use ($request,$result) {
            $record = Result::whereKey($result->id)->lockForUpdate()->firstOrFail();
            abort_unless($record->status === 'draft',409,'Result already published.');
            $record->update(['status'=>'published','published_by'=>$request->user()->id,'published_at'=>now()]);
        });
        return back()->with('success','Result published to the student.');
    }
    private function check(Request $request, Result $result): void
    {
        $staff = $request->user()->can('result.view') && AccessScope::allows($request->user(),$result->exam->institution_id);
        $owner = $request->user()->id === $result->student_id && $result->status === 'published';
        abort_unless($staff || $owner,403);
    }
    private function validateScore(string $score, string $total): string
    {
        if (GradeService::cents($score) > GradeService::cents($total)) {
            throw \Illuminate\Validation\ValidationException::withMessages(['score'=>'Marks exceed the total for this exam.']);
        }
        return number_format(GradeService::cents($score)/100,2,'.','');
    }
}

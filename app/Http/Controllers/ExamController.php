<?php
namespace App\Http\Controllers;
use App\Models\Blueprint;
use App\Models\Exam;
use App\Services\ExamGenerationService;
use App\Support\AccessScope;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class ExamController extends Controller
{
    public function __construct(private readonly ExamGenerationService $generator) {}
    public function index(Request $request)
    {
        abort_unless($request->user()->can('exam.view'),403);
        return view('exams.index',['exams'=>AccessScope::restrict(Exam::with('subject'),$request->user())->latest()->paginate(15)]);
    }
    public function generate(Request $request, Blueprint $blueprint)
    {
        abort_unless($request->user()->can('exam.generate') && AccessScope::allows($request->user(),$blueprint->institution_id),403);
        $exam = $this->generator->generate($blueprint,$request->user());
        return redirect()->route('exams.show',$exam)->with('success','Paper generated. Review it before issuing.');
    }
    public function show(Request $request, Exam $exam)
    {
        $this->check($request,$exam,'exam.view');
        return view('exams.show',['exam'=>$exam->load(['questions','subject'])]);
    }
    public function printPaper(Request $request, Exam $exam)
    {
        $this->check($request,$exam,'exam.view');
        return view('exams.print', ['exam'=>$exam->load(['questions','subject','institution'])]);
    }
    public function editPaper(Request $request, Exam $exam)
    {
        $this->check($request,$exam,'exam.issue');
        return view('exams.settings',['exam'=>$exam]);
    }
    public function updatePaper(Request $request, Exam $exam)
    {
        $this->check($request,$exam,'exam.issue');
        $data = $request->validate([
            'exam_label'=>['nullable','string','max:255'],
            'class_label'=>['nullable','string','max:255'],
            'paper_code'=>['nullable','string','max:60'],
            'instructions'=>['nullable','string','max:3000'],
            'print_layout'=>['required','in:standard,compact_mcq,creative'],
            'online_enabled'=>['required','boolean'],
            'online_start_at'=>['nullable','date'],
            'online_end_at'=>['nullable','date'],
        ]);
        if (! empty($data['online_start_at']) && ! empty($data['online_end_at'])
            && \Illuminate\Support\Carbon::parse($data['online_end_at'])->lte(\Illuminate\Support\Carbon::parse($data['online_start_at']))) {
            return back()->withErrors(['online_end_at'=>'The online closing time must be after the opening time.'])->withInput();
        }
        if ($exam->status !== 'issued' && $data['online_enabled']) {
            return back()->withErrors(['online_enabled'=>'Issue the paper before opening it to students.'])->withInput();
        }
        if ($exam->status === 'issued' && $data['online_enabled'] && $exam->questions()->count() === 0) {
            return back()->withErrors(['online_enabled'=>'Cannot publish an empty paper.'])->withInput();
        }
        $data['online_enabled'] = (bool)$data['online_enabled'];
        $exam->update($data);
        return redirect()->route('exams.show',$exam)->with('success','Paper layout and online schedule updated.');
    }
    public function answers(Request $request, Exam $exam)
    {
        $this->check($request,$exam,'exam.answers');
        return view('exams.answers',['exam'=>$exam->load('questions')]);
    }
    public function issue(Request $request, Exam $exam)
    {
        $this->check($request,$exam,'exam.issue');
        DB::transaction(function () use ($exam,$request) {
            $paper = Exam::whereKey($exam->id)->lockForUpdate()->firstOrFail();
            abort_unless($paper->status === 'draft',409,'This exam has already been issued.');
            abort_if($paper->questions()->count() === 0,409,'An empty paper cannot be issued.');
            $paper->update(['status'=>'issued','issued_by'=>$request->user()->id,'issued_at'=>now()]);
        });
        return back()->with('success','Exam issued. Its question snapshots are now fixed.');
    }
    private function check(Request $request, Exam $exam, string $permission): void
    {
        abort_unless($request->user()->can($permission) && AccessScope::allows($request->user(),$exam->institution_id),403);
    }
}

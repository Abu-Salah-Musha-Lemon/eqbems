<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\OnlineExamAnswer;
use App\Models\OnlineExamAttempt;
use App\Models\Result;
use App\Services\GradeService;
use App\Support\AccessScope;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OnlineExamController extends Controller
{
    public function index(Request $request)
    {
        $this->studentOnly($request);
        $exams = Exam::with('subject')->where('institution_id', $request->user()->institution_id)
            ->where('status', 'issued')->where('online_enabled', true)->latest()->paginate(15);
        $attempts = OnlineExamAttempt::where('student_id', $request->user()->id)->get()->keyBy('exam_id');
        return view('online.index', compact('exams', 'attempts'));
    }

    public function start(Request $request, Exam $exam)
    {
        $this->studentCanAccess($request, $exam);
        $this->assertAvailable($exam);
        OnlineExamAttempt::firstOrCreate(
            ['exam_id'=>$exam->id, 'student_id'=>$request->user()->id],
            ['started_at'=>now(),'status'=>'in_progress']
        );
        return redirect()->route('online-exams.take', $exam);
    }

    public function take(Request $request, Exam $exam)
    {
        $this->studentCanAccess($request, $exam);
        $attempt = OnlineExamAttempt::where('exam_id',$exam->id)
            ->where('student_id',$request->user()->id)->firstOrFail();
        if ($attempt->status === 'in_progress' && $this->timeOver($attempt, $exam)) {
            DB::transaction(fn () => $this->finish($attempt->fresh()));
            $attempt->refresh();
        }
        if ($attempt->status !== 'in_progress') {
            return redirect()->route('online-exams.index')->with('success','Your exam has been submitted.');
        }
        $answers = $attempt->answers()->get()->keyBy('exam_question_id');
        $deadline = $this->deadline($attempt,$exam);
        return view('online.take', ['exam'=>$exam->load(['questions','institution','subject']),
            'attempt'=>$attempt,'answers'=>$answers,'deadline'=>$deadline]);
    }

    public function save(Request $request, Exam $exam)
    {
        return $this->record($request, $exam, false);
    }

    public function submit(Request $request, Exam $exam)
    {
        return $this->record($request, $exam, true);
    }

    public function attempts(Request $request)
    {
        abort_unless($request->user()->can('result.mark'), 403);
        $attempts = OnlineExamAttempt::with(['student','exam.subject'])
            ->whereHas('exam', fn ($q) => AccessScope::restrict($q, $request->user()))
            ->whereIn('status',['submitted','graded'])->latest()->paginate(20);
        return view('online.attempts', compact('attempts'));
    }

    public function grade(Request $request, OnlineExamAttempt $attempt)
    {
        $this->staffCanGrade($request, $attempt);
        abort_unless($attempt->status === 'submitted', 409, 'This attempt is not awaiting grading.');
        return view('online.grade', [
            'attempt'=>$attempt->load(['student','exam.questions','answers.question']),
            'answers'=>$attempt->answers->keyBy('exam_question_id'),
        ]);
    }

    public function storeGrade(Request $request, OnlineExamAttempt $attempt)
    {
        $this->staffCanGrade($request, $attempt);
        $data = $request->validate(['marks'=>['required','array']]);
        DB::transaction(function () use ($attempt,$data,$request) {
            $attempt = OnlineExamAttempt::whereKey($attempt->id)->lockForUpdate()->firstOrFail();
            abort_unless($attempt->status === 'submitted',409,'This attempt was already graded.');
            $questions = $attempt->exam->questions()->get();
            $answers = $attempt->answers()->get()->keyBy('exam_question_id');
            $total = 0;
            foreach ($questions as $question) {
                $content = $question->content_snapshot;
                $answer = $answers->get($question->id) ?: $attempt->answers()->create([
                    'exam_question_id'=>$question->id,'response'=>null,
                ]);
                if (in_array($content['type'] ?? '', ['mcq','true_false'],true)) {
                    $score = $this->objectiveMarks($question,$answer->response);
                } else {
                    $value = $data['marks'][$question->id] ?? null;
                    if (($content['format'] ?? '') === 'creative') {
                        if (! is_array($value)) {
                            throw ValidationException::withMessages(['marks'=>'Enter a mark for each creative question part.']);
                        }
                        $partScores=[]; $score=0;
                        foreach ($content['creative_parts'] ?? [] as $part) {
                            $partValue=$value[$part['key']] ?? null;
                            $points=$this->checkedCents($partValue, (string)$part['marks']);
                            $score+=$points;
                            $partScores[$part['key']]=number_format($points/100,2,'.','');
                        }
                        $answer->update(['awarded_parts'=>$partScores]);
                    } else {
                        $score=$this->checkedCents($value,(string)$question->marks);
                    }
                    if ($score > GradeService::cents((string)$question->marks)) {
                        throw ValidationException::withMessages(['marks'=>'Awarded marks exceed the question maximum.']);
                    }
                }
                $total += $score;
                $answer->update(['awarded_marks'=>number_format($score / 100,2,'.','')]);
            }
            $this->completeGrading($attempt, $total, $request->user()->id);
        });
        return redirect()->route('online-attempts.index')->with('success','Online exam graded. Draft result is ready for publication.');
    }

    private function record(Request $request, Exam $exam, bool $submit)
    {
        $this->studentCanAccess($request,$exam);
        $data = $request->validate([
            'responses'=>['nullable','array','max:250'],
            'responses.*'=>['nullable'],
        ]);
        $expired = false;
        DB::transaction(function () use ($request,$exam,$data,$submit,&$expired) {
            $attempt = OnlineExamAttempt::where('exam_id',$exam->id)
                ->where('student_id',$request->user()->id)->lockForUpdate()->firstOrFail();
            abort_unless($attempt->status === 'in_progress',409,'This attempt has already been submitted.');
            if ($this->timeOver($attempt,$exam)) {
                $this->finish($attempt);
                $expired = true;
                return;
            }
            $questions = $exam->questions()->get()->keyBy('id');
            foreach (($data['responses'] ?? []) as $id => $response) {
                if (! ctype_digit((string)$id) || ! $questions->has((int)$id)) {
                    throw ValidationException::withMessages(['responses'=>'Response does not belong to this exam.']);
                }
                $question = $questions->get((int)$id);
                $clean = $this->cleanResponse($question->content_snapshot, $response);
                $attempt->answers()->updateOrCreate(['exam_question_id'=>(int)$id],['response'=>$clean]);
            }
            if ($submit) $this->finish($attempt);
        });
        return $submit || $expired
            ? redirect()->route('online-exams.index')->with('success',$expired ? 'Time expired; saved answers were submitted.' : 'Your answers were submitted.')
            : back()->with('success','Answers saved. They are not submitted yet.');
    }

    private function cleanResponse(array $question, mixed $response): array
    {
        if (($question['format'] ?? '') === 'creative') {
            if (! is_array($response)) throw ValidationException::withMessages(['responses'=>'Creative answers must have four parts.']);
            $clean=[];
            foreach (['ক','খ','গ','ঘ'] as $letter) {
                $value = $response[$letter] ?? '';
                if (! is_string($value) || mb_strlen($value) > 10000) {
                    throw ValidationException::withMessages(['responses'=>'Creative answer parts must be text of at most 10,000 characters.']);
                }
                $clean[$letter] = trim($value);
            }
            return ['parts'=>$clean];
        }
        if (! is_string($response) || mb_strlen($response) > 10000) {
            throw ValidationException::withMessages(['responses'=>'Answer must be text of at most 10,000 characters.']);
        }
        $response = trim($response);
        if (($question['type'] ?? '') === 'mcq' && $response !== '' && ! in_array($response,['A','B','C','D'],true)) {
            throw ValidationException::withMessages(['responses'=>'Choose one of the four MCQ options.']);
        }
        if (($question['type'] ?? '') === 'true_false' && $response !== '' && ! in_array($response,['True','False'],true)) {
            throw ValidationException::withMessages(['responses'=>'Choose True or False.']);
        }
        return ['text'=>$response];
    }

    private function finish(OnlineExamAttempt $attempt): void
    {
        $attempt = OnlineExamAttempt::whereKey($attempt->id)->lockForUpdate()->firstOrFail();
        if ($attempt->status !== 'in_progress') return;
        $total=0; $pendingWritten=false;
        $answers=$attempt->answers()->get()->keyBy('exam_question_id');
        foreach ($attempt->exam->questions as $question) {
            $answer = $answers->get($question->id) ?: $attempt->answers()->create(['exam_question_id'=>$question->id,'response'=>null]);
            if (in_array($question->content_snapshot['type'] ?? '',['mcq','true_false'],true)) {
                $score=$this->objectiveMarks($question,$answer->response);
                $answer->update(['awarded_marks'=>number_format($score/100,2,'.','')]);
                $total+=$score;
            } else {
                // A written answer requires independent assessment. Empty responses receive zero.
                $hasAnswer = (bool) collect($answer->response ?? [])->flatten()->filter(fn ($v) => filled($v))->count();
                if ($hasAnswer) { $pendingWritten=true; $answer->update(['awarded_marks'=>null]); }
                else $answer->update(['awarded_marks'=>'0.00']);
            }
        }
        $attempt->update(['status'=>'submitted','submitted_at'=>now()]);
        if (! $pendingWritten) $this->completeGrading($attempt,$total,null);
    }

    private function checkedCents(mixed $value, string $maximum): int
    {
        if (! is_string($value) && ! is_int($value) && ! is_float($value)) {
            throw ValidationException::withMessages(['marks'=>'Enter a valid numeric mark for each question.']);
        }
        $string=(string)$value;
        if (! preg_match('/^\d+(?:\.\d{1,2})?$/',$string)) {
            throw ValidationException::withMessages(['marks'=>'Marks must be non-negative numbers with at most two decimals.']);
        }
        $amount=GradeService::cents($string);
        if ($amount > GradeService::cents($maximum)) {
            throw ValidationException::withMessages(['marks'=>'Marks exceed the permitted maximum.']);
        }
        return $amount;
    }

    private function objectiveMarks($question, ?array $response): int
    {
        $answer = strtoupper(trim((string)($response['text'] ?? '')));
        $correct = strtoupper(trim((string)$question->answer_snapshot));
        return $answer !== '' && hash_equals($correct,$answer) ? GradeService::cents((string)$question->marks) : 0;
    }

    private function completeGrading(OnlineExamAttempt $attempt, int $totalCents, ?int $actorId): void
    {
        $score=number_format($totalCents/100,2,'.','');
        $existing=Result::where('exam_id',$attempt->exam_id)->where('student_id',$attempt->student_id)->first();
        abort_if($existing,409,'A result already exists for this student and exam. Resolve it before auto-grading.');
        Result::create([
            'exam_id'=>$attempt->exam_id,'student_id'=>$attempt->student_id,
            'score'=>$score,'grade'=>GradeService::grade($score,(string)$attempt->exam->total_marks),
            'status'=>'draft','marked_by'=>$actorId,
        ]);
        $attempt->update(['status'=>'graded','awarded_marks'=>$score,'graded_at'=>now()]);
    }

    private function studentOnly(Request $request): void
    {
        abort_unless($request->user()->hasRole('Student') && $request->user()->institution_id !== null,403);
    }
    private function studentCanAccess(Request $request, Exam $exam): void
    {
        $this->studentOnly($request);
        abort_unless($exam->status === 'issued' && $exam->online_enabled
            && (int)$exam->institution_id === (int)$request->user()->institution_id,403);
    }
    private function staffCanGrade(Request $request, OnlineExamAttempt $attempt): void
    {
        abort_unless($request->user()->can('result.mark') && AccessScope::allows($request->user(),$attempt->exam->institution_id),403);
    }
    private function assertAvailable(Exam $exam): void
    {
        abort_if($exam->online_start_at && now()->lt($exam->online_start_at),403,'This exam has not opened yet.');
        abort_if($exam->online_end_at && now()->gte($exam->online_end_at),403,'This exam has closed.');
    }
    private function deadline(OnlineExamAttempt $attempt, Exam $exam): \Illuminate\Support\Carbon
    {
        $limit=$attempt->started_at->copy()->addMinutes($exam->duration_minutes);
        return $exam->online_end_at && $exam->online_end_at->lt($limit) ? $exam->online_end_at : $limit;
    }
    private function timeOver(OnlineExamAttempt $attempt, Exam $exam): bool
    {
        return now()->gte($this->deadline($attempt,$exam));
    }
}

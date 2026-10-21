<?php
namespace App\Services;
use App\Models\Blueprint;
use App\Models\Exam;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
class ExamGenerationService
{
    public function __construct(private readonly BlueprintPoolService $pool) {}

    public function generate(Blueprint $blueprint, User $actor): Exam
    {
        return DB::transaction(function () use ($blueprint,$actor) {
            // Lock the blueprint while resolving manually selected questions.
            // A changed/ineligible selection must never be silently replaced.
            $locked = Blueprint::whereKey($blueprint->id)->lockForUpdate()->firstOrFail();
            $query = $this->pool->forBlueprint($locked);
            $eligible = (clone $query)->count();
            if ($eligible < $locked->question_count) {
                throw ValidationException::withMessages(['blueprint' => "Cannot generate exam: {$locked->question_count} approved questions required; only {$eligible} eligible for the selected subject/filters."]);
            }
            $selectedIds = $locked->selectedQuestions()->pluck('questions.id')->all();
            $pinned = $selectedIds
                ? (clone $query)->whereIn('question_id', $selectedIds)->get()->keyBy('question_id')
                : collect();
            if ($pinned->count() !== count($selectedIds)) {
                throw ValidationException::withMessages(['blueprint' => 'A selected question is no longer approved or does not match the blueprint filters. Remove it or update the blueprint before generating.']);
            }
            if ($pinned->count() > $locked->question_count) {
                throw ValidationException::withMessages(['blueprint' => 'Too many manually selected questions for this blueprint.']);
            }
            // Every pinned question is guaranteed to be included. Fill the
            // remaining slots randomly from OTHER eligible questions only.
            $manualVersions = collect($selectedIds)->map(fn ($id) => $pinned->get($id));
            $additional = (clone $query)->whereNotIn('question_id', $selectedIds)
                ->inRandomOrder()->limit($locked->question_count - $manualVersions->count())->get();
            $selected = $manualVersions->concat($additional);
            $totalCents = $selected->sum(fn ($v) => \App\Services\GradeService::cents((string)$v->marks));
            $exam = Exam::create([
                'institution_id'=>$blueprint->institution_id,'blueprint_id'=>$blueprint->id,
                'subject_id'=>$blueprint->subject_id,'title'=>$blueprint->title,
                'duration_minutes'=>$blueprint->duration_minutes,
                'total_marks'=>number_format($totalCents / 100, 2, '.', ''),
                'status'=>'draft','created_by'=>$actor->id,
            ]);
            foreach ($selected->values() as $index => $version) {
                $exam->questions()->create([
                    'question_version_id'=>$version->id,'position'=>$index + 1,'marks'=>$version->marks,
                    // Candidate-facing content is snapshotted without answers.
                    'content_snapshot'=>[
                        'body'=>$version->body,'type'=>$version->type,
                        'options'=>$version->options,'difficulty'=>$version->difficulty,
                        'format'=>$version->format ?? 'standard',
                        'stimulus'=>$version->stimulus, 'creative_parts'=>$version->creative_parts,
                    ],
                    'answer_snapshot'=>$version->answer_text,
                ]);
            }
            return $exam;
        });
    }
}

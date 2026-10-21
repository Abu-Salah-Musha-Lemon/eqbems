<?php

namespace App\Http\Controllers;

use App\Models\Blueprint;
use App\Models\Question;
use App\Services\BlueprintPoolService;
use App\Support\AccessScope;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BlueprintQuestionController extends Controller
{
    public function __construct(private readonly BlueprintPoolService $pool) {}

    /** A subject-scoped question bank with submit/review/select actions directly inside a blueprint. */
    public function index(Request $request, Blueprint $blueprint)
    {
        $this->authorizeAccess($request, $blueprint, 'blueprint.view');
        abort_unless($request->user()->can('question.view'), 403);

        $filters = $request->validate([
            'status' => ['nullable', 'in:draft,submitted,approved,rejected'],
            'selected' => ['nullable', 'in:1'],
        ]);

        $selectedIds = $blueprint->selectedQuestions()->pluck('questions.id')->map(fn ($id) => (int) $id)->all();
        $query = Question::with(['latestVersion', 'author'])
            ->where('institution_id', $blueprint->institution_id)
            ->where('subject_id', $blueprint->subject_id);

        if (!empty($filters['status'])) {
            $query->whereHas('latestVersion', fn ($q) => $q->where('status', $filters['status']));
        }
        if (!empty($filters['selected'])) {
            $query->whereIn('id', $selectedIds);
        }

        $questions = $query->latest('id')->paginate(15)->withQueryString();
        // Compute availability for only the questions shown on this page.
        $eligibleVersions = $this->pool->forBlueprint($blueprint)
            ->whereIn('question_id', $questions->pluck('id')->all())
            ->get(['id', 'question_id', 'version_number', 'type', 'difficulty', 'format'])
            ->keyBy('question_id');

        $availableCount = $this->pool->forBlueprint($blueprint)->count();
        $eligibleSelectedCount = $this->pool->forBlueprint($blueprint)
            ->whereIn('question_id', $selectedIds)->count();

        return view('blueprints.questions', compact(
            'blueprint', 'questions', 'selectedIds', 'eligibleVersions',
            'availableCount', 'eligibleSelectedCount'
        ));
    }

    public function attach(Request $request, Blueprint $blueprint, Question $question)
    {
        $this->authorizeAccess($request, $blueprint, 'blueprint.manage');
        abort_unless(
            (int) $question->institution_id === (int) $blueprint->institution_id
            && (int) $question->subject_id === (int) $blueprint->subject_id,
            403
        );

        DB::transaction(function () use ($blueprint, $question) {
            $locked = Blueprint::whereKey($blueprint->id)->lockForUpdate()->firstOrFail();
            if ($locked->selectedQuestions()->where('questions.id', $question->id)->exists()) {
                return; // Repeat clicks must not create duplicate selections.
            }
            if (!$this->pool->forBlueprint($locked)->where('question_id', $question->id)->exists()) {
                throw ValidationException::withMessages([
                    'question' => 'This question is not approved or does not match the blueprint type, format, or difficulty.',
                ]);
            }
            if ($locked->selectedQuestions()->count() >= $locked->question_count) {
                throw ValidationException::withMessages([
                    'question' => 'The blueprint already has the requested number of selected questions. Remove one or increase the question count.',
                ]);
            }
            $locked->selectedQuestions()->attach($question->id);
        });

        return back()->with('success', 'Approved question added to the blueprint.');
    }

    public function detach(Request $request, Blueprint $blueprint, Question $question)
    {
        $this->authorizeAccess($request, $blueprint, 'blueprint.manage');
        abort_unless(
            (int) $question->institution_id === (int) $blueprint->institution_id
            && (int) $question->subject_id === (int) $blueprint->subject_id,
            403
        );
        DB::transaction(function () use ($blueprint, $question) {
            $locked = Blueprint::whereKey($blueprint->id)->lockForUpdate()->firstOrFail();
            $locked->selectedQuestions()->detach($question->id);
        });

        return back()->with('success', 'Question removed from blueprint selection. Generated exams remain unchanged.');
    }

    private function authorizeAccess(Request $request, Blueprint $blueprint, string $permission): void
    {
        abort_unless($request->user()->can($permission)
            && AccessScope::allows($request->user(), $blueprint->institution_id), 403);
    }
}

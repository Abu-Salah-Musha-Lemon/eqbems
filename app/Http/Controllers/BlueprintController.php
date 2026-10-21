<?php

namespace App\Http\Controllers;

use App\Models\Blueprint;
use App\Models\Exam;
use App\Models\Subject;
use App\Services\BlueprintPoolService;
use App\Support\AccessScope;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BlueprintController extends Controller
{
    public function __construct(private readonly BlueprintPoolService $pool) {}

    public function index(Request $request)
    {
        abort_unless($request->user()->can('blueprint.view'), 403);

        $blueprints = AccessScope::restrict(
            Blueprint::with('subject'), $request->user()
        )->latest()->paginate(15);

        // Use precisely the same eligibility rules as actual exam generation.
        $availableCounts = [];
        $selectionCounts = [];
        $invalidSelectionCounts = [];
        foreach ($blueprints as $blueprint) {
            $availableCounts[$blueprint->id] = $this->pool->forBlueprint($blueprint)->count();
            $selectedIds = $blueprint->selectedQuestions()->pluck('questions.id')->all();
            $selectionCounts[$blueprint->id] = count($selectedIds);
            $eligibleSelected = $this->pool->forBlueprint($blueprint)->whereIn('question_id', $selectedIds)->count();
            $invalidSelectionCounts[$blueprint->id] = count($selectedIds) - $eligibleSelected;
        }

        return view('blueprints.index', compact('blueprints', 'availableCounts', 'selectionCounts', 'invalidSelectionCounts'));
    }

    public function create(Request $request)
    {
        abort_unless($request->user()->can('blueprint.manage'), 403);

        return view('blueprints.form', [
            'blueprint' => new Blueprint(),
            'subjects' => $this->subjects($request),
        ]);
    }

    /** JSON count used by the form's live availability preview. */
    public function availability(Request $request)
    {
        abort_unless($request->user()->can('blueprint.manage'), 403);
        $filters = $request->validate([
            'subject_id' => ['required', 'integer', Rule::exists('subjects', 'id')],
            'type' => ['nullable', Rule::in(['mcq', 'true_false', 'short'])],
            'difficulty' => ['nullable', Rule::in(['easy', 'medium', 'hard'])],
            'format' => ['nullable', Rule::in(['standard', 'creative'])],
        ]);
        $subject = $this->allowedSubject($request, (int) $filters['subject_id']);
        if (($filters['format'] ?? null) === 'creative'
            && ! in_array($filters['type'] ?? null, [null, 'short'], true)) {
            return response()->json(['message' => 'Creative/CQ requires Short Answer or All types.'], 422);
        }

        $count = $this->pool->query(
            (int) $subject->institution_id,
            (int) $subject->id,
            $filters['type'] ?? null,
            $filters['difficulty'] ?? null,
            $filters['format'] ?? null,
        )->count();

        return response()->json(['available' => $count]);
    }

    public function store(Request $request)
    {
        abort_unless($request->user()->can('blueprint.manage'), 403);

        $data = $this->validated($request);
        $subject = $this->allowedSubject($request, (int) $data['subject_id']);

        $blueprint = Blueprint::create($data + [
            'institution_id' => $subject->institution_id,
            'created_by' => $request->user()->id,
        ]);

        return redirect()->route('blueprints.index')
            ->with('success', 'Blueprint created. You can edit it before generating a paper.')
            ->with('warning', $this->availabilityWarning($blueprint));
    }

    public function edit(Request $request, Blueprint $blueprint)
    {
        $this->authorizeBlueprint($request, $blueprint, 'blueprint.manage');

        return view('blueprints.form', [
            'blueprint' => $blueprint,
            'subjects' => $this->subjects($request, (int) $blueprint->institution_id),
        ]);
    }

    public function update(Request $request, Blueprint $blueprint)
    {
        $this->authorizeBlueprint($request, $blueprint, 'blueprint.manage');

        $data = $this->validated($request);
        $subject = $this->allowedSubject($request, (int) $data['subject_id']);

        // A blueprint belongs to one institution for its entire lifetime.
        // Changing institutions could expose existing exams across tenants.
        if ((int) $subject->institution_id !== (int) $blueprint->institution_id) {
            return back()->withErrors(['subject_id' => 'The subject must belong to the blueprint\'s original institution.'])
                ->withInput();
        }

        $selectedIds = $blueprint->selectedQuestions()->pluck('questions.id')->all();
        if (count($selectedIds) > (int) $data['question_count']) {
            return back()->withErrors(['question_count' => 'Remove selected questions before decreasing the blueprint count below the number selected.'])->withInput();
        }
        $stillEligible = $this->pool->query(
            (int) $blueprint->institution_id, (int) $subject->id,
            $data['type'], $data['difficulty'], $data['format']
        )->whereIn('question_id', $selectedIds)->count();
        if ($stillEligible !== count($selectedIds)) {
            return back()->withErrors(['filters' => 'One or more selected questions do not match the new subject, type, format, or difficulty. Remove those selections first.'])->withInput();
        }

        $blueprint->update($data);

        return redirect()->route('blueprints.index')
            ->with('success', 'Blueprint updated. Existing generated exams are unchanged.')
            ->with('warning', $this->availabilityWarning($blueprint));
    }

    public function destroy(Request $request, Blueprint $blueprint)
    {
        $this->authorizeBlueprint($request, $blueprint, 'blueprint.manage');

        if (Exam::where('blueprint_id', $blueprint->id)->exists()) {
            return back()->with('error', 'This blueprint has generated exam history and cannot be deleted.');
        }

        $blueprint->delete();

        return redirect()->route('blueprints.index')->with('success', 'Blueprint removed.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'subject_id' => ['required', 'integer', Rule::exists('subjects', 'id')],
            'title' => ['required', 'string', 'max:255'],
            'question_count' => ['required', 'integer', 'min:1', 'max:200'],
            'duration_minutes' => ['required', 'integer', 'min:1', 'max:600'],
            'type' => ['nullable', Rule::in(['mcq', 'true_false', 'short'])],
            'difficulty' => ['nullable', Rule::in(['easy', 'medium', 'hard'])],
            'format' => ['nullable', Rule::in(['standard', 'creative'])],
        ]);

        if (($data['format'] ?? null) === 'creative'
            && ! in_array($data['type'] ?? null, [null, 'short'], true)) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'format' => 'Creative/CQ format requires Short Answer or All types.',
            ]);
        }

        // Always overwrite optional filters; otherwise clearing a filter during
        // edit would leave the previously saved value in the database.
        foreach (['type', 'difficulty', 'format'] as $filter) {
            $data[$filter] = $data[$filter] ?? null;
        }

        return $data;
    }

    private function allowedSubject(Request $request, int $subjectId): Subject
    {
        $subject = Subject::findOrFail($subjectId);
        abort_unless(AccessScope::allows($request->user(), $subject->institution_id), 403);
        return $subject;
    }

    private function authorizeBlueprint(Request $request, Blueprint $blueprint, string $permission): void
    {
        abort_unless($request->user()->can($permission)
            && AccessScope::allows($request->user(), $blueprint->institution_id), 403);
    }

    private function subjects(Request $request, ?int $institutionId = null)
    {
        $query = AccessScope::restrict(Subject::query(), $request->user());
        if ($institutionId !== null) {
            $query->where('institution_id', $institutionId);
        }
        return $query->orderBy('name')->get();
    }

    private function availabilityWarning(Blueprint $blueprint): ?string
    {
        $available = $this->pool->forBlueprint($blueprint)->count();
        if ($available < $blueprint->question_count) {
            return "This blueprint needs {$blueprint->question_count} matching approved questions, but only {$available} are available. Approve more questions or edit the filters before generating.";
        }
        return null;
    }
}

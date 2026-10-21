<?php
namespace App\Http\Controllers;
use App\Models\Subject;
use App\Models\Institution;
use App\Support\AccessScope;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;
class SubjectController extends Controller
{
    public function index(Request $request)
    {
        abort_unless($request->user()->can('academic.view'), 403);
        return view('subjects.index', ['subjects' => AccessScope::restrict(Subject::with('institution')->withCount('topics'), $request->user())->orderBy('name')->paginate(15)]);
    }
    public function create(Request $request)
    {
        abort_unless($request->user()->can('academic.manage'), 403);
        return view('subjects.form', ['institutions' => $this->institutions($request), 'subject' => new Subject]);
    }
    public function store(Request $request)
    {
        abort_unless($request->user()->can('academic.manage'), 403);
        $data = $this->validated($request);
        Subject::create($data);
        return redirect()->route('subjects.index')->with('success', 'Subject added.');
    }
    public function show(Request $request, Subject $subject)
    {
        $this->check($request, $subject, 'academic.view');
        return view('subjects.show', ['subject' => $subject->load(['institution', 'topics'])]);
    }
    public function edit(Request $request, Subject $subject)
    {
        $this->check($request, $subject, 'academic.manage');
        return view('subjects.form', ['subject' => $subject, 'institutions' => $this->institutions($request)]);
    }
    public function update(Request $request, Subject $subject)
    {
        $this->check($request, $subject, 'academic.manage');
        $data = $this->validated($request, $subject);
        abort_unless($subject->institution_id == $data['institution_id'], 422, 'Cannot move an existing subject to another institution.');
        $subject->update($data);
        return redirect()->route('subjects.show', $subject)->with('success', 'Subject updated.');
    }
    public function destroy(Request $request, Subject $subject)
    {
        $this->check($request, $subject, 'academic.manage');
        if ($subject->topics()->exists() || $subject->questions()->exists()) return back()->with('error', 'Remove dependent topics/questions before deleting.');
        try { $subject->delete(); } catch (QueryException $e) { return back()->with('error', 'Subject is referenced by existing records.'); }
        return redirect()->route('subjects.index')->with('success', 'Subject removed.');
    }
    private function check(Request $request, Subject $subject, string $permission): void
    {
        abort_unless($request->user()->can($permission) && AccessScope::allows($request->user(), $subject->institution_id), 403);
    }
    private function validated(Request $request, ?Subject $subject = null): array
    {
        $request->merge(['code'=>strtoupper(trim((string)$request->input('code','')))]);
        $data = $request->validate([
            'institution_id' => ['nullable','integer','exists:institutions,id'],
            'code' => ['required','string','max:60',Rule::unique('subjects')->where('institution_id', $request->user()->institution_id ?? $request->input('institution_id'))->ignore($subject?->id)],
            'name' => ['required','string','max:255'],
            'description' => ['nullable','string','max:2000'],
        ]);
        $data['institution_id'] = AccessScope::isGlobal($request->user()) ? ($data['institution_id'] ?? null) : $request->user()->institution_id;
        abort_unless(AccessScope::allows($request->user(), $data['institution_id']), 403);
        abort_unless(Institution::whereKey($data['institution_id'])->where('status', true)->exists(), 422, 'Select an active institution.');
        $data['code'] = strtoupper(trim($data['code']));
        return $data;
    }
    private function institutions(Request $request)
    {
        return AccessScope::restrict(Institution::query(), $request->user(), 'id')->where('status', true)->orderBy('name')->get();
    }
}

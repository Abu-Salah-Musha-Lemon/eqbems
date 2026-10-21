<?php
namespace App\Http\Controllers;
use App\Models\AcademicYear;
use App\Models\Institution;
use App\Support\AccessScope;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class AcademicYearController extends Controller
{
    public function index(Request $request)
    {
        abort_unless($request->user()->can('academic.view'), 403);
        return view('years.index', ['years' => AccessScope::restrict(AcademicYear::query(), $request->user())->latest()->paginate(20), 'institutions' => AccessScope::restrict(Institution::query(), $request->user(), 'id')->get()]);
    }
    public function store(Request $request)
    {
        abort_unless($request->user()->can('academic.manage'), 403);
        $data = $request->validate([
            'institution_id' => ['nullable','integer','exists:institutions,id'],
            'label' => ['required','string','max:80'],
            'starts_on' => ['required','date'],
            'ends_on' => ['required','date','after:starts_on'],
        ]);
        $data['institution_id'] = AccessScope::isGlobal($request->user()) ? ($data['institution_id'] ?? null) : $request->user()->institution_id;
        abort_unless(AccessScope::allows($request->user(), $data['institution_id']), 403);
        $request->validate(['label' => [Rule::unique('academic_years')->where('institution_id', $data['institution_id'])]]);
        AcademicYear::create($data);
        return back()->with('success', 'Academic year created.');
    }
    public function destroy(Request $request, AcademicYear $academicYear)
    {
        abort_unless($request->user()->can('academic.manage') && AccessScope::allows($request->user(), $academicYear->institution_id), 403);
        $academicYear->delete();
        return back()->with('success', 'Academic year removed.');
    }
}

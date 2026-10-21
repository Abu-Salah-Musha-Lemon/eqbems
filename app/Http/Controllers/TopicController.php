<?php
namespace App\Http\Controllers;
use App\Models\Subject;
use App\Models\Topic;
use App\Support\AccessScope;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class TopicController extends Controller
{
    public function store(Request $request, Subject $subject)
    {
        abort_unless($request->user()->can('academic.manage') && AccessScope::allows($request->user(), $subject->institution_id), 403);
        $data = $request->validate(['name' => ['required','string','max:255', Rule::unique('topics','name')->where('subject_id', $subject->id)]]);
        $subject->topics()->create($data);
        return back()->with('success', 'Topic added.');
    }
    public function destroy(Request $request, Subject $subject, Topic $topic)
    {
        abort_unless($request->user()->can('academic.manage') && AccessScope::allows($request->user(), $subject->institution_id), 403);
        abort_unless($topic->subject_id === $subject->id, 404);
        if (\App\Models\Question::where('topic_id', $topic->id)->exists()) return back()->with('error', 'Topic is in use by questions.');
        $topic->delete();
        return back()->with('success', 'Topic removed.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Subject;
use App\Support\AccessScope;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class QuestionReviewController extends Controller
{
    /** Only pending versions that this user is permitted to review. */
    public function index(Request $request)
    {
        abort_unless($request->user()->can('question.review') && $request->user()->can('question.view'), 403);

        $data = $request->validate([
            'subject_id' => ['nullable', 'integer', Rule::exists('subjects', 'id')],
        ]);
        $user = $request->user();

        $questions = AccessScope::restrict(
            Question::query()->with(['subject', 'author', 'latestVersion']), $user
        )
            ->where(function ($query) use ($user) {
                $query->whereNull('author_id')->orWhere('author_id', '!=', $user->id);
            })
            ->whereHas('latestVersion', function ($query) use ($user) {
                $query->where('status', 'submitted')
                    ->where(function ($author) use ($user) {
                        $author->whereNull('created_by')->orWhere('created_by', '!=', $user->id);
                    });
            });

        if (!empty($data['subject_id'])) {
            $questions->where('subject_id', $data['subject_id']);
        }

        $subjects = AccessScope::restrict(Subject::query(), $user)->orderBy('name')->get();
        $pendingCount = (clone $questions)->count();
        $questions = $questions->latest('id')->paginate(15)->withQueryString();

        return view('questions.review-queue', compact('questions', 'subjects', 'pendingCount'));
    }
}

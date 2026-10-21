<?php
namespace App\Http\Controllers;
use App\Models\Institution;
use App\Models\User;
use App\Models\Subject;
use App\Models\Question;
use App\Models\Exam;
use App\Models\Result;
use App\Support\AccessScope;
class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $institutionCount = $user->can('institution.view') ? AccessScope::restrict(Institution::query(),$user,'id')->count() : 0;
        $userCount = AccessScope::isGlobal($user) ? User::count() : 0;
        $subjectCount = $user->can('academic.view') ? AccessScope::restrict(Subject::query(),$user)->count() : 0;
        $questionCount = $user->can('question.view') ? AccessScope::restrict(Question::query(),$user)->count() : 0;
        $examCount = $user->can('exam.view') ? AccessScope::restrict(Exam::query(),$user)->count() : 0;
        $resultCount = $user->can('result.view') ? Result::whereHas('exam',fn ($q)=>AccessScope::restrict($q,$user))->count() : Result::where('student_id',$user->id)->where('status','published')->count();
        return view('dashboard.index',compact('userCount','institutionCount','subjectCount','questionCount','examCount','resultCount'));
    }
}

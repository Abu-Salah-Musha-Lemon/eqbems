@extends('layouts.admin')
@section('title','Overview')
@section('content')
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
<div><div class="eyebrow">Workspace / Overview</div><h1 class="page-title">Welcome, {{ auth()->user()->name }}</h1><p class="muted mb-0">Your EQBEMS workspace, scoped to your access permissions.</p></div>
</div>
<div class="row g-3 mb-4">
@foreach([['Users',$userCount,'people'],['Institutions',$institutionCount,'buildings'],['Subjects',$subjectCount,'book'],['Questions',$questionCount,'journal-text'],['Examinations',$examCount,'clipboard-check'],['Results',$resultCount,'award']] as $metric)
<div class="col-6 col-xl-4"><div class="card-pro stat-card"><div class="stat-top"><span class="stat-label">{{ strtoupper($metric[0]) }}</span><span class="stat-icon"><i class="bi bi-{{ $metric[2] }}"></i></span></div><div class="stat-value">{{ number_format($metric[1]) }}</div></div></div>
@endforeach
</div>
<div class="card-pro p-4"><h2 class="section-title mb-3">Continue your work</h2><div class="row g-3">
@can('academic.view')<div class="col-md-4"><a class="quick-action" href="{{ route('subjects.index') }}"><i class="bi bi-book"></i><span><strong>Subjects and topics</strong><small>Organize academic classifications</small></span></a></div>@endcan
@can('question.view')<div class="col-md-4"><a class="quick-action" href="{{ route('questions.index') }}"><i class="bi bi-journal-text"></i><span><strong>Question bank</strong><small>Draft, review and approve</small></span></a></div>@endcan
@can('blueprint.view')<div class="col-md-4"><a class="quick-action" href="{{ route('blueprints.index') }}"><i class="bi bi-diagram-3"></i><span><strong>Paper blueprints</strong><small>Set exam requirements</small></span></a></div>@endcan
@can('exam.view')<div class="col-md-4"><a class="quick-action" href="{{ route('exams.index') }}"><i class="bi bi-clipboard-check"></i><span><strong>Examinations</strong><small>Review generated papers</small></span></a></div>@endcan
<div class="col-md-4"><a class="quick-action" href="{{ route('results.index') }}"><i class="bi bi-award"></i><span><strong>Results</strong><small>View published results or grade candidates</small></span></a></div>
@role('Super Admin')<div class="col-md-4"><a class="quick-action" href="{{ route('users.index') }}"><i class="bi bi-people"></i><span><strong>Users and roles</strong><small>Manage platform access</small></span></a></div>@endrole
</div></div>
@endsection

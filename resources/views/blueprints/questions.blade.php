@extends('layouts.admin')
@section('title', 'Manage blueprint questions')
@section('content')
@php
    $selectedCount = count($selectedIds);
    $invalidCount = $selectedCount - $eligibleSelectedCount;
    $remaining = max(0, $blueprint->question_count - $selectedCount);
    $canManage = auth()->user()->can('blueprint.manage');
@endphp
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
        <div class="eyebrow">Blueprints / {{ $blueprint->subject?->name }}</div>
        <h1 class="page-title">Manage questions: {{ $blueprint->title }}</h1>
        <p class="muted mb-0">Create a question, submit it for independent approval, then add an approved question to the paper.</p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('blueprints.index') }}" class="btn btn-light">Back to blueprints</a>
        @can('question.review')
            <a href="{{ route('questions.review-queue', ['subject_id' => $blueprint->subject_id]) }}" class="btn btn-outline-success">
                <i class="bi bi-check2-square me-1"></i> Review pending questions
            </a>
        @endcan
        @can('question.create')
            <a href="{{ route('questions.create', ['blueprint_id' => $blueprint->id]) }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Add new question
            </a>
        @endcan
        @can('blueprint.manage')
            <a href="{{ route('blueprints.edit', $blueprint) }}" class="btn btn-outline-secondary">Edit blueprint</a>
        @endcan
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success" role="status">{{ session('success') }}</div>
@endif
@if($errors->any())
    <div class="alert alert-danger" role="alert">
        <strong>Action not completed.</strong>
        <ul class="mb-0 mt-1">@foreach($errors->all() as $message)<li>{{ $message }}</li>@endforeach</ul>
    </div>
@endif

<div class="row g-3 mb-4">
    <div class="col-sm-4"><div class="card-pro p-3"><div class="muted small">Required</div><div class="fs-3 fw-bold">{{ $blueprint->question_count }}</div></div></div>
    <div class="col-sm-4"><div class="card-pro p-3"><div class="muted small">Matching approved</div><div class="fs-3 fw-bold">{{ $availableCount }}</div></div></div>
    <div class="col-sm-4"><div class="card-pro p-3"><div class="muted small">Manually selected</div><div class="fs-3 fw-bold">{{ $selectedCount }}</div></div></div>
</div>

@if($invalidCount > 0)
    <div class="alert alert-danger">
        {{ $invalidCount }} selected question(s) no longer match an approved version and the current blueprint filters.
        Filter by <strong>Selected only</strong> and remove outdated selections before generating.
    </div>
@elseif($availableCount < $blueprint->question_count)
    <div class="alert alert-warning">You need {{ $blueprint->question_count - $availableCount }} additional approved matching questions to generate this exam.</div>
@else
    <div class="alert alert-success">The approved question pool is sufficient. You can manually select up to {{ $remaining }} more question(s), or let generation fill the remaining slots randomly.</div>
@endif
<div class="alert alert-light border small">
    <strong>Approval workflow:</strong> An author creates a draft → submits it → a <em>different</em> authorized reviewer approves it → the Blueprint manager adds it to the selection.
    Drafts and submitted questions cannot be selected. Selected questions use their latest approved version when the paper is generated.
</div>

<form method="GET" action="{{ route('blueprints.questions.index', $blueprint) }}" class="card-pro p-3 mb-3">
    <div class="row g-2 align-items-end">
        <div class="col-md-5"><label for="status" class="form-label">Review status</label>
            <select class="form-select" id="status" name="status">
                <option value="">All statuses</option>
                @foreach(['draft'=>'Draft', 'submitted'=>'Submitted for approval', 'approved'=>'Approved', 'rejected'=>'Rejected'] as $key => $label)
                    <option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4"><label for="selected" class="form-label">Selected in blueprint</label>
            <select class="form-select" id="selected" name="selected">
                <option value="">Show all</option>
                <option value="1" @selected(request('selected') === '1')>Selected only</option>
            </select>
        </div>
        <div class="col-md-3"><button class="btn btn-primary w-100" type="submit">Filter questions</button></div>
    </div>
</form>

<div class="card-pro table-card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead><tr><th>Question</th><th>Status</th><th>Eligible version</th><th>Blueprint</th><th>Actions</th></tr></thead>
            <tbody>
            @forelse($questions as $question)
                @php
                    $latest = $question->latestVersion;
                    $eligible = $eligibleVersions->get($question->id);
                    $picked = in_array($question->id, $selectedIds, true);
                    $isAuthor = (int) $question->author_id === (int) auth()->id();
                    $isVersionAuthor = (int) $latest?->created_by === (int) auth()->id();
                @endphp
                <tr>
                    <td style="min-width:240px">
                        <a class="fw-semibold" href="{{ route('questions.show', $question) }}">#{{ $question->id }} · {{ \Illuminate\Support\Str::limit($latest?->body ?? 'No text', 140) }}</a>
                        <div class="small muted mt-1">{{ strtoupper($latest?->type ?? '—') }} / {{ ucfirst($latest?->difficulty ?? '—') }} / {{ ucfirst($latest?->format ?? 'standard') }}</div>
                    </td>
                    <td><span class="badge text-bg-light border">{{ ucfirst($latest?->status ?? 'unknown') }} (v{{ $latest?->version_number ?? '?' }})</span></td>
                    <td>
                        @if($eligible)<span class="badge text-bg-success">Approved v{{ $eligible->version_number }}</span>
                        @else<span class="small text-danger">Not eligible</span>@endif
                    </td>
                    <td>
                        @if($picked)<span class="badge text-bg-primary">Selected</span>
                        @else<span class="small muted">Not selected</span>@endif
                    </td>
                    <td>
                        <div class="d-flex gap-2 flex-wrap">
                            @if($latest?->status === 'draft' && ($isAuthor || \App\Support\AccessScope::isGlobal(auth()->user())) && auth()->user()->can('question.create'))
                                <form method="POST" action="{{ route('questions.submit', $question) }}">@csrf
                                    <button class="btn btn-sm btn-outline-primary">Submit for approval</button>
                                </form>
                            @endif
                            @if($latest?->status === 'submitted' && auth()->user()->can('question.review') && !$isAuthor && !$isVersionAuthor)
                                <form method="POST" action="{{ route('questions.review', $question) }}">@csrf
                                    <input type="hidden" name="decision" value="approved">
                                    <button class="btn btn-sm btn-success">Approve</button>
                                </form>
                                <a href="{{ route('questions.show', $question) }}" class="btn btn-sm btn-outline-danger" title="Read the full question, answer key, and provide a rejection reason">Review / Reject</a>
                            @endif
                            @if($latest?->status === 'submitted' && ($isAuthor || $isVersionAuthor))
                                <span class="small muted">Awaiting another reviewer's approval</span>
                            @endif
                            @if($canManage)
                                @if($picked)
                                    <form method="POST" action="{{ route('blueprints.questions.detach', [$blueprint, $question]) }}">@csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Remove</button>
                                    </form>
                                @elseif($eligible && $remaining > 0)
                                    <form method="POST" action="{{ route('blueprints.questions.attach', [$blueprint, $question]) }}">@csrf
                                        <button class="btn btn-sm btn-outline-primary">Add to blueprint</button>
                                    </form>
                                @endif
                            @endif
                            <a href="{{ route('questions.show', $question) }}" class="btn btn-sm btn-light">Open</a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center muted py-5">No questions found. Choose “Add new question” to create one for this subject.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $questions->links() }}</div>
</div>

<p class="small muted mt-3">Approval does not automatically add a question to a blueprint. Use <strong>Add to blueprint</strong> to guarantee inclusion; any remaining slots are filled from the eligible approved pool when you generate the exam.</p>
@endsection

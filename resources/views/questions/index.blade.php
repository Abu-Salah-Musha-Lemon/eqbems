@extends('layouts.admin')
@section('title', 'Question bank')
@section('content')
<div class="d-flex justify-content-between flex-wrap align-items-center gap-3 mb-4">
    <div><div class="eyebrow">Examination / Content</div><h1 class="page-title">Question Bank</h1><p class="muted">Create, submit, and independently review versioned questions.</p></div>
    <div class="d-flex flex-wrap gap-2">
        @can('question.review')<a href="{{ route('questions.review-queue') }}" class="btn btn-outline-primary"><i class="bi bi-check2-square me-1"></i> Review pending questions</a>@endcan
        @can('question.create')<a href="{{ route('questions.create') }}" class="btn btn-primary">+ Create question</a>@endcan
    </div>
</div>
<form method="GET" action="{{ route('questions.index') }}" class="card-pro p-3 mb-3">
    <div class="row g-2 align-items-end">
        <div class="col-lg-4"><label for="q" class="form-label">Search questions</label><input id="q" name="q" value="{{ request('q') }}" class="form-control" placeholder="Question text..."></div>
        <div class="col-lg-3"><label for="subject_id" class="form-label">Subject</label><select id="subject_id" class="form-select" name="subject_id"><option value="">All subjects</option>@foreach($subjects as $subject)<option value="{{ $subject->id }}" @selected((string)request('subject_id') === (string)$subject->id)>{{ $subject->name }}</option>@endforeach</select></div>
        <div class="col-lg-3"><label for="status" class="form-label">Review status</label><select id="status" class="form-select" name="status">
            <option value="">All statuses</option>
            @foreach(['draft' => 'Draft', 'submitted' => 'Submitted', 'approved' => 'Approved', 'rejected' => 'Rejected'] as $value => $label)
                <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
            @endforeach
        </select></div>
        <div class="col-lg-2"><button class="btn btn-primary w-100" type="submit">Filter</button></div>
    </div>
</form>
<div class="card-pro table-card">
    <div class="table-responsive"><table class="table align-middle"><thead><tr><th>Question</th><th>Subject</th><th>Version</th><th>Status</th><th>Difficulty</th><th>Actions</th></tr></thead><tbody>
        @forelse($questions as $question)
            @php
                $latest = $question->latestVersion;
                $canReview = $latest?->status === 'submitted' && auth()->user()->can('question.review')
                    && (int)$question->author_id !== (int)auth()->id()
                    && (int)$latest->created_by !== (int)auth()->id();
            @endphp
            <tr>
                <td><a href="{{ route('questions.show', $question) }}">{{ \Illuminate\Support\Str::limit($latest?->body, 85) }}</a></td>
                <td>{{ $question->subject?->name }}</td>
                <td>v{{ $latest?->version_number }}</td>
                <td><span class="badge text-bg-light border">{{ ucfirst($latest?->status ?? 'Unknown') }}</span></td>
                <td>{{ ucfirst($latest?->difficulty ?? '—') }}</td>
                <td>
                    @if($canReview)<a class="btn btn-sm btn-success" href="{{ route('questions.show', $question) }}">Review / Approve</a>
                    @else<a class="btn btn-sm btn-light" href="{{ route('questions.show', $question) }}">Open</a>@endif
                </td>
            </tr>
        @empty<tr><td colspan="6" class="text-center muted py-4">No questions match your filters.</td></tr>@endforelse
    </tbody></table></div>
    <div class="p-3">{{ $questions->links() }}</div>
</div>
@endsection

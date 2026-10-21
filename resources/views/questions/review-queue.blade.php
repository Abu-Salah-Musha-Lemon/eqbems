@extends('layouts.admin')
@section('title', 'Review questions')
@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
        <div class="eyebrow">Examination / Independent review</div>
        <h1 class="page-title">Question Review Queue</h1>
        <p class="muted mb-0">Review submitted questions from other authors in your institution. Your own submissions cannot be approved by you.</p>
    </div>
    <a href="{{ route('questions.index') }}" class="btn btn-light">Back to Question Bank</a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
@endif

<div class="card-pro p-3 mb-3">
    <div class="d-flex justify-content-between flex-wrap gap-3 align-items-center mb-3">
        <div><div class="muted small">Waiting for your review</div><div class="fs-3 fw-bold">{{ $pendingCount }}</div></div>
        <span class="badge text-bg-light border">Submitted versions only</span>
    </div>
    <form method="GET" action="{{ route('questions.review-queue') }}" class="row g-2 align-items-end">
        <div class="col-md-9">
            <label for="subject_id" class="form-label">Filter by subject</label>
            <select name="subject_id" id="subject_id" class="form-select">
                <option value="">All accessible subjects</option>
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}" @selected((string)request('subject_id') === (string)$subject->id)>{{ $subject->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3"><button type="submit" class="btn btn-primary w-100">Filter</button></div>
    </form>
</div>

<div class="card-pro table-card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead><tr><th>Question</th><th>Subject</th><th>Submitted version</th><th>Author</th><th>Action</th></tr></thead>
            <tbody>
                @forelse($questions as $question)
                    <tr>
                        <td style="min-width:250px"><a href="{{ route('questions.show', $question) }}">#{{ $question->id }} · {{ \Illuminate\Support\Str::limit($question->latestVersion?->body, 130) }}</a></td>
                        <td>{{ $question->subject?->name }}</td>
                        <td><span class="badge text-bg-warning">Submitted (v{{ $question->latestVersion?->version_number }})</span></td>
                        <td>{{ $question->author?->name ?? 'Unassigned' }}</td>
                        <td><a class="btn btn-sm btn-primary" href="{{ route('questions.show', $question) }}">Review / Approve / Reject</a></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center py-5">
                        <strong>No questions are waiting for your review.</strong>
                        <div class="muted mt-2">A question must first be saved as a draft and submitted for approval by a different author. Already-approved sample questions will not appear here.</div>
                    </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $questions->links() }}</div>
</div>
@endsection

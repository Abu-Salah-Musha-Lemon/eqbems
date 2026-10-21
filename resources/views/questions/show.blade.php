@extends('layouts.admin')
@section('title','Question review')
@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4"><div><div class="eyebrow">Question bank / #{{ $question->id }}</div><h1 class="page-title">Question details</h1><p class="muted">{{ $question->subject->name }} / {{ $question->topic?->name ?? 'Uncategorized' }}</p></div><div class="d-flex gap-2">@can('question.review')<a class="btn btn-outline-primary" href="{{ route('questions.review-queue') }}">Back to Review Queue</a>@endcan<a class="btn btn-light" href="{{ route('questions.index') }}">Back to questions</a></div></div>
@if(session('success'))
    <div class="alert alert-success" role="status">{{ session('success') }}</div>
@endif
@if($errors->any())
    <div class="alert alert-danger" role="alert">
        <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
@endif
@php($latest=$question->latestVersion)
<div class="card-pro p-4 mb-4"><div class="d-flex gap-2 mb-3"><span class="badge text-bg-primary">Version {{ $latest->version_number }}</span><span class="badge text-bg-light border">{{ ucfirst($latest->status) }}</span><span class="badge text-bg-light border">{{ ucfirst($latest->difficulty) }}</span><span class="badge text-bg-light border">{{ $latest->marks }} marks</span></div>
<h2 class="section-title mb-3">{{ $latest->body }}</h2>
@if(($latest->format ?? '') === 'creative')<div class="p-3 border rounded mb-3" style="white-space:pre-wrap"><strong>উদ্দীপক:</strong> {{ $latest->stimulus }}</div>@foreach($latest->creative_parts ?? [] as $part)<p><strong>{{ $part['key'] }}.</strong> {{ $part['text'] }} ({{ $part['marks'] }} marks)</p>@endforeach @endif
@if($latest->options)<div class="row g-2 mb-3">@foreach($latest->options as $key => $value)<div class="col-md-6"><div class="p-3 border rounded">{{ $key }}. {{ $value }}</div></div>@endforeach</div>@endif
<div class="border-top pt-3 mt-3"><strong>Answer key:</strong> {{ $latest->answer_text }} @if($latest->explanation)<p class="muted mt-2">{{ $latest->explanation }}</p>@endif</div>
@if($latest->review_note)<div class="alert alert-info mt-3">Review note: {{ $latest->review_note }}</div>@endif
<div class="d-flex gap-2 flex-wrap mt-4">
@if($latest->status === 'draft' && ($question->author_id === auth()->id() || \App\Support\AccessScope::isGlobal(auth()->user())) && auth()->user()->can('question.create'))
<a href="{{ route('questions.edit',$question) }}" class="btn btn-primary">Edit draft</a><form method="POST" action="{{ route('questions.submit',$question) }}">@csrf<button class="btn btn-outline-primary">Submit for review</button></form>
@endif
@if(in_array($latest->status,['approved','rejected']) && ($question->author_id === auth()->id() || \App\Support\AccessScope::isGlobal(auth()->user())) && auth()->user()->can('question.create'))<form method="POST" action="{{ route('questions.revise',$question) }}">@csrf<button class="btn btn-primary">Create new version</button></form>@endif
</div></div>
@if($latest->status === 'submitted' && ($question->author_id === auth()->id() || $latest->created_by === auth()->id()))
<div class="alert alert-info">This question is submitted and waiting for independent review. Its author cannot approve or reject it. Ask a reviewer with <code>question.review</code> permission from the same institution.</div>
@endif
@if($latest->status === 'submitted' && auth()->user()->can('question.review') && $question->author_id !== auth()->id() && $latest->created_by !== auth()->id())<div class="card-pro p-4 mb-4"><h2 class="section-title mb-3">Independent review</h2><form method="POST" action="{{ route('questions.review',$question) }}">@csrf<div class="mb-3"><label class="form-label">Feedback / rejection reason</label><textarea class="form-control" name="review_note" rows="3"></textarea></div><button name="decision" value="approved" class="btn btn-success me-2">Approve</button><button name="decision" value="rejected" class="btn btn-outline-danger">Reject</button></form></div>@endif
<div class="card-pro p-4"><h2 class="section-title mb-3">Version history</h2><table class="table"><thead><tr><th>Version</th><th>Status</th><th>Reviewed</th><th>Review note</th></tr></thead><tbody>@foreach($question->versions->sortByDesc('version_number') as $version)<tr><td>v{{ $version->version_number }}</td><td>{{ ucfirst($version->status) }}</td><td>{{ $version->reviewed_at?->format('d M Y H:i') ?? '—' }}</td><td>{{ $version->review_note ?? '—' }}</td></tr>@endforeach</tbody></table></div>
@endsection

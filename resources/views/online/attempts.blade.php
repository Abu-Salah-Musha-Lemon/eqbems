@extends('layouts.admin')
@section('title','Online attempt grading')
@section('content')
<div class="eyebrow">Assessment / Online</div><h1 class="page-title">Submitted online attempts</h1>
<p class="muted mb-4">Objective answers are auto-marked. Teachers grade submitted written/CQ answers before results can be published.</p>
<div class="card-pro table-card"><table class="table"><thead><tr><th>Student</th><th>Exam</th><th>Submitted</th><th>Status</th><th>Action</th></tr></thead><tbody>
@forelse($attempts as $attempt)<tr><td>{{ $attempt->student->name }}</td><td>{{ $attempt->exam->title }}</td><td>{{ $attempt->submitted_at?->format('d M Y h:i A') }}</td><td>{{ ucwords(str_replace('_',' ',$attempt->status)) }}</td><td>@if($attempt->status==='submitted')<a class="btn btn-sm btn-primary" href="{{ route('online-attempts.grade',$attempt) }}">Review & grade</a>@else<span class="badge text-bg-success">Graded</span>@endif</td></tr>
@empty<tr><td colspan="5" class="text-center muted py-4">No submitted attempts.</td></tr>@endforelse
</tbody></table>{{ $attempts->links() }}</div>
@endsection

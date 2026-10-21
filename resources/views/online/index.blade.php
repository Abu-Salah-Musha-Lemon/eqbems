@extends('layouts.admin')
@section('title','My online exams')
@section('content')
<div class="eyebrow">Student examination portal</div><h1 class="page-title">Available online exams</h1>
<p class="muted mb-4">Only exams issued to your institution appear here. You have one attempt per exam. Opening an attempt starts its timer.</p>
<div class="card-pro table-card"><table class="table"><thead><tr><th>Exam</th><th>Duration / marks</th><th>Window</th><th>Attempt</th></tr></thead><tbody>
@forelse($exams as $exam)
@php($attempt=$attempts->get($exam->id))
@php($isOpen=(!$exam->online_start_at || now()->gte($exam->online_start_at)) && (!$exam->online_end_at || now()->lt($exam->online_end_at)))
<tr><td><strong>{{ $exam->title }}</strong><div class="muted small">{{ $exam->subject->name }}</div></td><td>{{ $exam->duration_minutes }} minutes · {{ (float)$exam->total_marks }} marks</td><td><div>Opens: {{ $exam->online_start_at?->format('d M Y h:i A') ?? 'Anytime' }}</div><div>Closes: {{ $exam->online_end_at?->format('d M Y h:i A') ?? 'No fixed date' }}</div></td><td>
@if($attempt && $attempt->status !== 'in_progress')<span class="badge text-bg-success">Submitted</span>
@elseif($attempt)<a class="btn btn-primary btn-sm" href="{{ route('online-exams.take',$exam) }}">Continue attempt</a>
@elseif($isOpen)<form method="POST" action="{{ route('online-exams.start',$exam) }}">@csrf<button class="btn btn-primary btn-sm" data-confirm="Starting begins your examination timer. Continue?">Start exam</button></form>
@else<span class="badge text-bg-secondary">Not open</span>@endif
</td></tr>
@empty<tr><td colspan="4" class="text-center muted py-4">No published online exams yet.</td></tr>@endforelse
</tbody></table>{{ $exams->links() }}</div>
@endsection

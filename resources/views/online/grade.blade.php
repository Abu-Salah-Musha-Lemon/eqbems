@extends('layouts.admin')
@section('title','Grade online attempt')
@section('content')
<div class="eyebrow">Online examination / Grading</div><h1 class="page-title">{{ $attempt->exam->title }}</h1>
<p class="muted mb-4">Student: <strong>{{ $attempt->student->name }}</strong> · Full marks: {{ (float)$attempt->exam->total_marks }}. Correct answers are visible to authorized graders only.</p>
<form action="{{ route('online-attempts.grade.store',$attempt) }}" method="POST">@csrf
@foreach($attempt->exam->questions as $item)
@php($c=$item->content_snapshot)
@php($answer=$answers->get($item->id))
<div class="card-pro p-4 mb-3"><div class="d-flex justify-content-between"><strong>{{ $item->position }}. {{ $c['body'] ?? '' }}</strong><span>{{ (float)$item->marks }} marks</span></div>
@if(($c['format'] ?? '') === 'creative')
<div class="bg-light border p-3 my-3" style="white-space:pre-wrap"><strong>উদ্দীপক:</strong> {{ $c['stimulus'] ?? '' }}</div>
@foreach($c['creative_parts'] ?? [] as $part)
<div class="mb-3"><strong>{{ $part['key'] }}. {{ $part['text'] }}</strong><p class="border rounded p-2 mt-2" style="white-space:pre-wrap">{{ $answer?->response['parts'][$part['key']] ?? '— No answer —' }}</p><label class="form-label">{{ $part['key'] }} part marks (max {{ $part['marks'] }})</label><input type="number" class="form-control" style="max-width:220px" name="marks[{{ $item->id }}][{{ $part['key'] }}]" min="0" max="{{ (float)$part['marks'] }}" step="0.01" value="{{ old('marks.'.$item->id.'.'.$part['key'],$answer?->awarded_parts[$part['key']] ?? '0') }}" required></div>
@endforeach
@else
<div class="border rounded p-3 my-3" style="white-space:pre-wrap">{{ $answer?->response['text'] ?? '— No answer —' }}</div>
@endif
<div class="small text-muted">Model answer (staff only): {{ $item->answer_snapshot }}</div>
@if(in_array($c['type'] ?? '', ['mcq','true_false'],true))<div class="mt-2"><strong>Automatically awarded:</strong> {{ (float)($answer?->awarded_marks ?? 0) }}</div>
@elseif(($c['format'] ?? '') !== 'creative')<div class="mt-3" style="max-width:280px"><label class="form-label">Award marks for this question (0–{{ (float)$item->marks }})</label><input type="number" class="form-control" name="marks[{{ $item->id }}]" min="0" max="{{ (float)$item->marks }}" step="0.01" value="{{ old('marks.'.$item->id,$answer?->awarded_marks ?? '0') }}" required></div>@endif
</div>
@endforeach
<button class="btn btn-primary" data-confirm="Finalize this grading and create a draft result?">Finish grading</button>
<a class="btn btn-light" href="{{ route('online-attempts.index') }}">Cancel</a>
</form>
@endsection

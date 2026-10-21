@extends('layouts.admin')
@section('title','Take online exam')
@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between mb-3 gap-2"><div><div class="eyebrow">Online examination</div><h1 class="page-title">{{ $exam->title }}</h1><p class="muted">{{ $exam->subject->name }} · {{ $exam->duration_minutes }} minutes · {{ (float)$exam->total_marks }} marks</p></div><div class="card-pro px-4 py-3 text-center"><small class="muted">Time remaining</small><div id="clock" style="font-size:1.5rem;font-weight:800" role="timer">—</div></div></div>
@if($exam->instructions)<div class="alert alert-info" style="white-space:pre-wrap">{{ $exam->instructions }}</div>@endif
<div class="alert alert-warning">Save your work regularly. Only <strong>Submit final answers</strong> completes your attempt. Written and creative answers need teacher grading; results appear after publication.</div>
<form method="POST" id="exam-form" action="{{ route('online-exams.save',$exam) }}">@csrf
@foreach($exam->questions as $item)
@php($c=$item->content_snapshot)
@php($saved=$answers->get($item->id)?->response ?? [])
<div class="card-pro p-4 mb-3"><div class="d-flex justify-content-between gap-2"><h2 style="font-size:1.05rem;font-weight:750">Question {{ $item->position }}</h2><span class="badge text-bg-light">{{ (float)$item->marks }} marks</span></div>
<p style="white-space:pre-wrap;font-size:1rem">{{ $c['body'] ?? '' }}</p>
@if(($c['format'] ?? '') === 'creative')
<div class="rounded border p-3 mb-3" style="white-space:pre-wrap"><strong>উদ্দীপক:</strong> {{ $c['stimulus'] ?? '' }}</div>
@foreach($c['creative_parts'] ?? [] as $part)
<div class="mb-3"><label class="form-label fw-semibold" for="q{{ $item->id }}_{{ $loop->index }}">{{ $part['key'] }}. {{ $part['text'] }} <span class="muted">({{ $part['marks'] }} marks)</span></label><textarea id="q{{ $item->id }}_{{ $loop->index }}" class="form-control" name="responses[{{ $item->id }}][{{ $part['key'] }}]" rows="3" maxlength="10000">{{ old('responses.'.$item->id.'.'.$part['key'], $saved['parts'][$part['key']] ?? '') }}</textarea></div>
@endforeach
@elseif(($c['type'] ?? '') === 'mcq')
<div class="row g-2">@foreach(($c['options'] ?? []) as $letter=>$option)<div class="col-lg-6"><label class="d-block p-3 rounded border"><input type="radio" name="responses[{{ $item->id }}]" value="{{ $letter }}" @checked(old('responses.'.$item->id,$saved['text'] ?? '')===$letter)> <strong>{{ ['A'=>'ক','B'=>'খ','C'=>'গ','D'=>'ঘ'][$letter] ?? $letter }}.</strong> {{ $option }}</label></div>@endforeach</div>
@elseif(($c['type'] ?? '') === 'true_false')
@foreach(['True'=>'True / সত্য','False'=>'False / মিথ্যা'] as $value=>$label)<label class="d-inline-block me-4"><input type="radio" name="responses[{{ $item->id }}]" value="{{ $value }}" @checked(old('responses.'.$item->id,$saved['text'] ?? '')===$value)> {{ $label }}</label>@endforeach
@else
<textarea class="form-control" name="responses[{{ $item->id }}]" rows="5" maxlength="10000" aria-label="Answer question {{ $item->position }}">{{ old('responses.'.$item->id,$saved['text'] ?? '') }}</textarea>
@endif
</div>
@endforeach
<div class="d-flex flex-wrap gap-2 justify-content-end mb-4"><button class="btn btn-outline-primary" type="submit">Save answers</button><button id="submit-answer" type="submit" formaction="{{ route('online-exams.submit',$exam) }}" class="btn btn-primary" onclick="return confirm('Submit final answers? You cannot edit after submitting.')">Submit final answers</button></div>
</form>
@push('scripts')
<script>
(function(){const end = new Date(@json($deadline->toIso8601String())).getTime();const clock=document.getElementById('clock');let alreadyExpired=false;
function tick(){const d=Math.max(0,Math.floor((end-Date.now())/1000));clock.textContent=String(Math.floor(d/3600)).padStart(2,'0')+':'+String(Math.floor((d%3600)/60)).padStart(2,'0')+':'+String(d%60).padStart(2,'0');
if(d===0 && !alreadyExpired){alreadyExpired=true;const form=document.getElementById('exam-form');form.action=@json(route('online-exams.submit',$exam));form.requestSubmit();}}
tick();setInterval(tick,1000);})();
</script>
@endpush
@endsection

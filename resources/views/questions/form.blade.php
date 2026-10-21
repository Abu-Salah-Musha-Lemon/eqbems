@extends('layouts.admin')
@section('title', $question->exists ? 'Edit draft' : 'New question')
@section('content')
<div class="eyebrow">Question bank / Draft</div><h1 class="page-title mb-4">{{ $question->exists ? 'Edit draft version '.$version->version_number : 'Create a question' }}</h1>
<form method="POST" action="{{ $question->exists ? route('questions.update',$question) : route('questions.store') }}">@csrf @if($question->exists)@method('PUT')@endif
@if(!$question->exists && !empty($blueprintContext))
    <input type="hidden" name="blueprint_id" value="{{ $blueprintContext->id }}">
    <div class="alert alert-info">Creating a draft for blueprint <strong>{{ $blueprintContext->title }}</strong>. Submit it for independent approval from the blueprint's question manager.</div>
@endif
<div class="card-pro p-4"><div class="row g-3">
<div class="col-md-6"><label class="form-label fw-semibold">Subject *</label><select name="subject_id" id="subject_id" required class="form-select"><option value="">Choose subject…</option>@foreach($subjects as $subject)<option value="{{ $subject->id }}" @selected(old('subject_id', $question->subject_id ?? ($blueprintContext->subject_id ?? null)) == $subject->id)>{{ $subject->name }} ({{ $subject->code }})</option>@endforeach</select></div>
<div class="col-md-6"><label class="form-label fw-semibold">Topic (optional)</label><select name="topic_id" class="form-select"><option value="">Not specified</option>@foreach($subjects as $subject)@foreach($subject->topics as $topic)<option value="{{ $topic->id }}" data-subject="{{ $subject->id }}" @selected(old('topic_id',$question->topic_id)==$topic->id)>{{ $subject->code }} / {{ $topic->name }}</option>@endforeach @endforeach</select></div>
@php($defaultType = old('type', $version->type ?? ($blueprintContext->type ?? (($blueprintContext->format ?? null) === 'creative' ? 'short' : 'mcq'))))
<div class="col-md-6"><label class="form-label fw-semibold">Question type *</label><select class="form-select" name="type" required><option value="mcq" @selected($defaultType === 'mcq')>Multiple choice (4 options)</option><option value="true_false" @selected($defaultType === 'true_false')>True / False</option><option value="short" @selected($defaultType === 'short')>Short answer</option></select></div>
<div class="col-md-3"><label class="form-label fw-semibold">Difficulty *</label><select class="form-select" name="difficulty">@foreach(['easy','medium','hard'] as $difficulty)<option @selected(old('difficulty',$version->difficulty ?? ($blueprintContext->difficulty ?? 'medium')) === $difficulty) value="{{ $difficulty }}">{{ ucfirst($difficulty) }}</option>@endforeach</select></div>
<div class="col-md-3"><label class="form-label fw-semibold">Marks *</label><input name="marks" type="number" min="0.01" max="99999.99" step="0.01" required class="form-control" value="{{ old('marks',$version->marks ?? '1.00') }}"></div>
<div class="col-md-6"><label class="form-label fw-semibold">Paper format</label><select class="form-select" name="format" id="question_format"><option value="standard" @selected(old('format',$version->format ?? ($blueprintContext->format ?? 'standard')) === 'standard')>Standard (MCQ / True-False / Short)</option><option value="creative" @selected(old('format',$version->format ?? ($blueprintContext->format ?? 'standard')) === 'creative')>Creative/CQ (উদ্দীপক and ক/খ/গ/ঘ)</option></select><div class="form-text">For Creative, choose the Short answer assessment type. Marks are automatically 1+2+3+4.</div></div>
<div class="col-12" id="creative_fields"><div class="p-3 rounded border"><label class="form-label fw-semibold">উদ্দীপক / Stimulus</label><textarea class="form-control mb-3" rows="4" name="stimulus">{{ old('stimulus',$version->stimulus ?? '') }}</textarea><div class="row g-3">@foreach(['a'=>'ক (1 mark)','b'=>'খ (2 marks)','c'=>'গ (3 marks)','d'=>'ঘ (4 marks)'] as $letter=>$label)<div class="col-lg-6"><label class="form-label">{{ $label }}</label><textarea class="form-control" rows="2" name="part_{{ $letter }}">{{ old('part_'.$letter, $version->creative_parts[array_search($letter, ['a','b','c','d'])]['text'] ?? '') }}</textarea></div>@endforeach</div></div></div>
<div class="col-12"><label class="form-label fw-semibold">Question text *</label><textarea name="body" class="form-control" rows="4" required>{{ old('body',$version->body) }}</textarea></div>
@foreach(['A','B','C','D'] as $letter)<div class="col-md-6"><label class="form-label">MCQ option {{ $letter }}</label><input class="form-control" name="option_{{ strtolower($letter) }}" value="{{ old('option_'.strtolower($letter),$version->options[$letter] ?? '') }}" placeholder="Choice {{ $letter }}"></div>@endforeach
<div class="col-md-6"><label class="form-label fw-semibold">Correct answer *</label><input class="form-control" name="answer_text" value="{{ old('answer_text',$version->answer_text) }}" required placeholder="MCQ: A/B/C/D · True/False · Short: model answer"><div class="form-text">The answer key is restricted to authorized staff.</div></div>
<div class="col-md-6"><label class="form-label">Explanation</label><textarea class="form-control" name="explanation" rows="2">{{ old('explanation',$version->explanation) }}</textarea></div>
</div><div class="d-flex gap-2 mt-4"><button class="btn btn-primary">Save draft</button><a href="{{ (!$question->exists && !empty($blueprintContext)) ? route('blueprints.questions.index', $blueprintContext) : route('questions.index') }}" class="btn btn-light">Cancel</a></div></div></form>
@push('scripts')
<script>
function toggleCreative() {
    document.getElementById('creative_fields').style.display = document.getElementById('question_format').value === 'creative' ? 'block' : 'none';
}
document.getElementById('question_format').addEventListener('change', toggleCreative);
toggleCreative();
</script>
@endpush
@endsection

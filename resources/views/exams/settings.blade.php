@extends('layouts.admin')
@section('title','Paper & online settings')
@section('content')
<div class="eyebrow">Examinations / {{ $exam->title }}</div>
<h1 class="page-title">Paper template and online exam settings</h1>
<p class="muted mb-4">Customize the printed question-paper header and decide whether students can take this issued exam online.</p>
<form method="POST" action="{{ route('exams.settings.update',$exam) }}">@csrf @method('PUT')
<div class="card-pro p-4"><div class="row g-3">
<div class="col-lg-6"><label class="form-label">Exam label / session</label><input class="form-control" name="exam_label" maxlength="255" value="{{ old('exam_label',$exam->exam_label) }}" placeholder="Second Term Examination – 2026"></div>
<div class="col-lg-6"><label class="form-label">Class or level</label><input class="form-control" name="class_label" value="{{ old('class_label',$exam->class_label) }}" placeholder="Class VIII / HSC / Semester 2"></div>
<div class="col-lg-6"><label class="form-label">Subject / paper code</label><input class="form-control" name="paper_code" value="{{ old('paper_code',$exam->paper_code) }}" placeholder="275"></div>
<div class="col-lg-6"><label class="form-label">Printed layout</label><select class="form-select" name="print_layout">@foreach(['standard'=>'School / standard','compact_mcq'=>'Compact MCQ (two columns)','creative'=>'Creative/CQ (stimulus and four parts)'] as $key=>$value)<option value="{{ $key }}" @selected(old('print_layout',$exam->print_layout ?? 'standard')===$key)>{{ $value }}</option>@endforeach</select></div>
<div class="col-12"><label class="form-label">Exam instructions</label><textarea class="form-control" name="instructions" rows="3" placeholder="Answer any five questions. No electronic devices allowed.">{{ old('instructions',$exam->instructions) }}</textarea><div class="form-text">Print and online views display this text. Do not include model answers.</div></div>
<div class="col-lg-4"><label class="form-label">Allow online attempts</label><select name="online_enabled" class="form-select"><option value="0" @selected(!old('online_enabled',$exam->online_enabled))>No — print only</option><option value="1" @selected(old('online_enabled',$exam->online_enabled))>Yes — student access</option></select><div class="form-text">Paper must be issued first. Only registered Student accounts of this institution can attempt.</div></div>
<div class="col-lg-4"><label class="form-label">Online opens (optional)</label><input name="online_start_at" class="form-control" type="datetime-local" value="{{ old('online_start_at',$exam->online_start_at?->format('Y-m-d\\TH:i')) }}"></div>
<div class="col-lg-4"><label class="form-label">Online closes (optional)</label><input name="online_end_at" class="form-control" type="datetime-local" value="{{ old('online_end_at',$exam->online_end_at?->format('Y-m-d\\TH:i')) }}"></div>
</div><div class="d-flex gap-2 mt-4"><button class="btn btn-primary">Save settings</button><a class="btn btn-light" href="{{ route('exams.show',$exam) }}">Back to exam</a></div></div>
</form>
@endsection

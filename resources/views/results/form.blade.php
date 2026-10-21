@extends('layouts.admin')
@section('title','Enter marks')
@section('content')
<h1 class="page-title mb-4">Enter candidate marks</h1>
<form method="POST" action="{{ route('results.store') }}">@csrf<div class="card-pro p-4"><div class="row g-3">
<div class="col-md-6"><label class="form-label fw-semibold">Issued exam</label><select name="exam_id" class="form-select" required><option value="">Choose exam…</option>@foreach($exams as $exam)<option value="{{ $exam->id }}" @selected(old('exam_id', request('exam_id')) == $exam->id)>{{ $exam->title }} (#{{ $exam->id }}, {{ $exam->total_marks }} marks)</option>@endforeach</select></div>
<div class="col-md-6"><label class="form-label fw-semibold">Student</label><select name="student_id" class="form-select" required><option value="">Choose student…</option>@foreach($students as $student)<option value="{{ $student->id }}" @selected(old('student_id')==$student->id)>{{ $student->name }} ({{ $student->email }})</option>@endforeach</select></div>
<div class="col-md-4"><label class="form-label fw-semibold">Marks earned</label><input name="score" type="number" min="0" step="0.01" class="form-control" value="{{ old('score') }}" required></div>
</div><p class="muted mt-3">Grades: A ≥80%, B ≥70%, C ≥60%, D ≥50%, F &lt;50%. Scores must not exceed exam marks.</p><button class="btn btn-primary">Record draft result</button></div></form>
@endsection

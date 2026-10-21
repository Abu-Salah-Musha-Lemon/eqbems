@extends('layouts.admin')
@section('title', $subject->exists ? 'Edit subject' : 'New subject')
@section('content')
<h1 class="page-title mb-4">{{ $subject->exists ? 'Edit subject' : 'Add subject' }}</h1>
<form method="POST" action="{{ $subject->exists ? route('subjects.update',$subject) : route('subjects.store') }}">@csrf @if($subject->exists)@method('PUT')@endif
<div class="card-pro p-4"><div class="row g-3">
<div class="col-md-6"><label class="form-label">Institution</label><select name="institution_id" class="form-select" required @disabled($subject->exists)><option value="">Choose…</option>@foreach($institutions as $institution)<option value="{{ $institution->id }}" @selected(old('institution_id',$subject->institution_id ?? auth()->user()->institution_id) == $institution->id)>{{ $institution->name }}</option>@endforeach</select>@if($subject->exists)<input type="hidden" name="institution_id" value="{{ $subject->institution_id }}">@endif</div>
<div class="col-md-6"><label class="form-label">Subject code</label><input name="code" class="form-control" required maxlength="60" value="{{ old('code',$subject->code) }}" placeholder="CSE101"></div>
<div class="col-12"><label class="form-label">Name</label><input name="name" class="form-control" required value="{{ old('name',$subject->name) }}"></div>
<div class="col-12"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="3">{{ old('description',$subject->description) }}</textarea></div>
</div><div class="mt-4 d-flex gap-2"><button class="btn btn-primary">Save subject</button><a href="{{ route('subjects.index') }}" class="btn btn-light">Cancel</a></div></div></form>
@endsection

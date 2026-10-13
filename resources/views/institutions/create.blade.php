@extends('layouts.admin')
@section('title','Add institution')
@section('content')
<div class="mb-4"><div class="eyebrow">Directory / Institutions</div><h1 class="page-title">Add institution</h1><p class="muted mb-0">Create a new school or organization record.</p></div>
<form method="POST" action="{{ route('institutions.store') }}" enctype="multipart/form-data">@csrf<div class="card-pro p-4 p-lg-5"><div class="mb-4"><h2 class="section-title">Institution details</h2><div class="section-sub">Fields marked with * are required.</div></div>@include('institutions._partials.form')<div class="d-flex flex-wrap gap-2 justify-content-end mt-4 pt-3 border-top"><a href="{{ route('institutions.index') }}" class="btn btn-light px-4">Cancel</a><button type="submit" class="btn btn-primary px-4"><i class="bi bi-check2 me-2"></i>Create institution</button></div></div></form>
@endsection

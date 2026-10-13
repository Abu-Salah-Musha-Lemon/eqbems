@extends('layouts.admin')
@section('title','User details')
@section('content')<div class="d-flex justify-content-between align-items-end mb-4">
    <div>
        <div class="eyebrow">System / Users</div>
        <h1 class="page-title">User details</h1>
    </div><a href="{{ route('users.index') }}" class="btn btn-light">Back to users</a>
</div>
<div class="card-pro p-4 p-lg-5">
    <div class="d-flex align-items-center gap-3">
        <div class="avatar" style="width:60px;height:60px;font-size:24px">{{ strtoupper(substr($user->name,0,1)) }}
        </div>
        <div>
            <h2 class="section-title" style="font-size:20px">{{ $user->name }}</h2>
            <div class="muted">{{ $user->email }}</div>
        </div>
    </div>
    <hr class="my-4">
    <div class="row g-4">
        <div class="col-md-6">
            <div class="muted small mb-1">Registered</div>
            <strong>{{ $user->created_at?->format('d M Y, h:i A') ?? '—' }}</strong>
        </div>
        <div class="col-md-6">
            <div class="muted small mb-1">Last updated</div>
            <strong>{{ $user->updated_at?->format('d M Y, h:i A') ?? '—' }}</strong>
        </div>
    </div><a href="{{ route('users.edit',$user) }}" class="btn btn-primary mt-4">Edit user</a>
</div>@endsection

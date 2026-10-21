@extends('layouts.admin')
@section('title','Archived institutions')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4"><div><div class="eyebrow">Institutions / Archive</div><h1 class="page-title">Archived institutions</h1><p class="muted">Restore soft-deleted institution records without discarding academic history.</p></div><a href="{{ route('institutions.index') }}" class="btn btn-light">Active institutions</a></div>
<div class="card-pro table-card"><table class="table"><thead><tr><th>Institution</th><th>Code</th><th>Archived on</th><th>Action</th></tr></thead><tbody>@forelse($institutions as $institution)<tr><td>{{ $institution->name }}</td><td>{{ $institution->code }}</td><td>{{ $institution->deleted_at?->format('d M Y') }}</td><td><form method="POST" action="{{ route('institutions.restore',$institution->uuid) }}">@csrf<button class="btn btn-sm btn-primary">Restore</button></form></td></tr>@empty<tr><td colspan="4" class="muted">No archived institutions.</td></tr>@endforelse</tbody></table>{{ $institutions->links() }}</div>
@endsection

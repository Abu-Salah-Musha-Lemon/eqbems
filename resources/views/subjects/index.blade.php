@extends('layouts.admin')
@section('title','Subjects')
@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4"><div><div class="eyebrow">Academic / Catalog</div><h1 class="page-title">Subjects</h1><p class="muted">Classify questions by subject and topic.</p></div>@can('academic.manage')<a class="btn btn-primary" href="{{ route('subjects.create') }}">+ New subject</a>@endcan</div>
<div class="card-pro table-card"><div class="table-responsive"><table class="table"><thead><tr><th>Code</th><th>Subject</th><th>Institution</th><th>Topics</th><th>Actions</th></tr></thead><tbody>
@forelse($subjects as $subject)<tr><td><span class="badge text-bg-light border">{{ $subject->code }}</span></td><td><a href="{{ route('subjects.show',$subject) }}">{{ $subject->name }}</a></td><td>{{ $subject->institution->name }}</td><td>{{ $subject->topics_count }}</td><td><a class="btn btn-sm btn-light" href="{{ route('subjects.show',$subject) }}">View</a> @can('academic.manage')<a class="btn btn-sm btn-outline-primary" href="{{ route('subjects.edit',$subject) }}">Edit</a>@endcan</td></tr>@empty<tr><td colspan="5" class="muted text-center py-4">No subjects yet.</td></tr>@endforelse
</tbody></table></div>{{ $subjects->links() }}</div>
@endsection

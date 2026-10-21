@extends('layouts.admin')
@section('title','Subject details')
@section('content')
<div class="d-flex justify-content-between gap-3 flex-wrap mb-4"><div><div class="eyebrow">Academic / {{ $subject->code }}</div><h1 class="page-title">{{ $subject->name }}</h1><p class="muted mb-0">{{ $subject->institution->name }} · {{ $subject->description }}</p></div>@can('academic.manage')<a class="btn btn-primary" href="{{ route('subjects.edit',$subject) }}">Edit subject</a>@endcan</div>
<div class="card-pro p-4 mb-4"><h2 class="section-title mb-3">Topics</h2>
<table class="table"><thead><tr><th>Topic</th><th>Actions</th></tr></thead><tbody>@forelse($subject->topics as $topic)<tr><td>{{ $topic->name }}</td><td>@can('academic.manage')<form method="POST" action="{{ route('topics.destroy',[$subject,$topic]) }}">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" type="submit">Remove</button></form>@endcan</td></tr>@empty<tr><td colspan="2" class="muted">No topics created.</td></tr>@endforelse</tbody></table>
@can('academic.manage')<form method="POST" action="{{ route('topics.store',$subject) }}" class="row g-2 mt-2">@csrf<div class="col-md-8"><label class="form-label">Add topic</label><input name="name" class="form-control" required maxlength="255" placeholder="e.g. Data structures"></div><div class="col-md-4 d-flex align-items-end"><button class="btn btn-primary">Add topic</button></div></form>@endcan
</div>
@can('academic.manage')<form method="POST" action="{{ route('subjects.destroy',$subject) }}">@csrf @method('DELETE')<button class="btn btn-outline-danger" data-confirm="Deletion is blocked if the subject is referenced by questions or examinations." type="submit">Delete subject</button></form>@endcan
@endsection

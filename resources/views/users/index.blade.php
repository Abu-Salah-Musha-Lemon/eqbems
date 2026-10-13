@extends('layouts.admin')
@section('title','User management')
@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
    <div>
        <div class="eyebrow">System / Access</div>
        <h1 class="page-title">User management</h1>
        <p class="muted mb-0">Manage accounts that can access EQBEMS.</p>
    </div><a href="{{ route('users.create') }}" class="btn btn-primary"><i class="bi bi-person-plus me-2"></i>Add
        user</a>
</div>
<div class="card-pro table-card">
    <div class="mb-3">
        <h2 class="section-title">All users</h2>
        <div class="section-sub">{{ $users->total() }} accounts · Search, sort and manage access.</div>
    </div>
    <div class="table-responsive">
        <table class="table" id="usersTable">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Email</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>@foreach($users as $user)<tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="avatar" style="width:34px;height:34px">{{ strtoupper(substr($user->name,0,1)) }}
                            </div>
                            <div class="fw-bold">{{ $user->name }} @if($user->id===auth()->id())<span
                                    class="badge text-bg-light border">You</span>@endif</div>
                        </div>
                    </td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->created_at?->format('d M Y') ?? '—' }}</td>
                    <td>
                        <div class="d-flex gap-1"><a class="icon-btn" title="View"
                                href="{{ route('users.show',$user) }}"><i class="bi bi-eye"></i></a><a class="icon-btn"
                                title="Edit" href="{{ route('users.edit',$user) }}"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="{{ route('users.destroy',$user) }}">@csrf
                                @method('DELETE')<button class="icon-btn text-danger"
                                    data-confirm="This account will be deleted." type="submit" title="Delete"
                                    {{ $user->id===auth()->id()?'disabled':'' }}><i class="bi bi-trash3"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>@endforeach</tbody>
        </table>
    </div>
    <div class="mt-3">{{ $users->links() }}</div>
</div>
@endsection
@push('scripts')<script>
    document.addEventListener('DOMContentLoaded', () => {
        const t = document.querySelector('#usersTable');
        if (t) new DataTable(t, {
            responsive: true,
            paging: false,
            info: false,
            language: {
                search: '',
                searchPlaceholder: 'Search users…'
            },
            columnDefs: [{
                targets: 3,
                orderable: false,
                searchable: false
            }]
        })
    });

</script>@endpush

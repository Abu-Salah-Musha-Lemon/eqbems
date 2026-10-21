
@extends('layouts.admin')
@section('title','Institutions')
@section('content')
<div class="container">
    <h1>Deleted Institutions</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('institutions.index') }}"
       class="btn btn-secondary mb-3">
        Back to Institutions
    </a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Code</th>
                <th>Deleted At</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($institutions as $institution)
                <tr>
                    <td>{{ $institution->name }}</td>
                    <td>{{ $institution->code }}</td>
                    <td>{{ $institution->deleted_at }}</td>
                    <td>
                        <form
                            action="{{ route('institutions.restore', $institution->uuid) }}"
                            method="POST"
                            class="d-inline"
                        >
                            @csrf
                            @method('PATCH')

                            <button type="submit" class="btn btn-success btn-sm">
                                Restore
                            </button>
                        </form>

                        <form
                            action="{{ route('institutions.forceDelete', $institution->uuid) }}"
                            method="POST"
                            class="d-inline"
                            onsubmit="return confirm('Permanently delete this institution? This cannot be undone.')"
                        >
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn btn-danger btn-sm">
                                Delete Permanently
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No deleted institutions found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $institutions->links() }}
</div>
@endsection

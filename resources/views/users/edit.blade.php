@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')

<div class="mb-4">
    <div class="eyebrow">System / Users</div>
    <h1 class="page-title">Edit User</h1>
    <p class="muted mb-0">
        Update {{ $user->name }}'s account, roles, and permissions.
    </p>
</div>

<form method="POST" action="{{ route('users.update', $user) }}">
    @csrf
    @method('PUT')

    {{-- SECTION 1: ACCOUNT DETAILS --}}
    <div class="card-pro p-4 p-lg-5 mb-4">
        <h2 class="section-title mb-4">Account Details</h2>

        @include('users._form')
    </div>

    {{-- SECTION 2: ROLE ASSIGNMENT --}}
    <div class="card-pro p-4 p-lg-5 mb-4">
        <h2 class="section-title mb-2">Role Assignment</h2>

        <p class="text-muted mb-4">
            Select one or more roles for this user.
        </p>

        <div class="row">
            @forelse ($roles as $role)
                <div class="col-md-6 col-lg-4 mb-3" wire:key="role-{{ $role->id }}">
                    <label class="border rounded p-3 d-flex align-items-start gap-2 h-100">
                        <input
                            type="checkbox"
                            name="roles[]"
                            value="{{ $role->name }}"
                            class="form-check-input mt-1"
                            @checked(in_array($role->name, old('roles', $userRoles)))
                        >

                        <span>
                            <strong>{{ $role->name }}</strong>
                            <br>
                            <small class="text-muted">
                                Assign the {{ $role->name }} role
                            </small>
                        </span>
                    </label>
                </div>
            @empty
                <div class="col-12">
                    <p class="text-warning">
                        No roles found. Please create roles first.
                    </p>
                </div>
            @endforelse
        </div>

        @error('roles')
            <div class="text-danger mt-2">{{ $message }}</div>
        @enderror

        @error('roles.*')
            <div class="text-danger mt-2">{{ $message }}</div>
        @enderror
    </div>

    {{-- SECTION 3: DIRECT PERMISSION ASSIGNMENT --}}
    <div class="card-pro p-4 p-lg-5 mb-4">
        <h2 class="section-title mb-2">Permission Assignment</h2>

        <p class="text-muted mb-4">
            Select individual permissions for this user.
            These are direct permissions, separate from permissions inherited
            through the user's roles.
        </p>

        <div class="row">
            @forelse ($permissions as $permission)
                <div class="col-md-6 col-lg-4 mb-3" wire:key="permission-{{ $permission->id }}">
                    <label class="border rounded p-3 d-flex align-items-center gap-2 h-100">
                        <input
                            type="checkbox"
                            name="permissions[]"
                            value="{{ $permission->name }}"
                            class="form-check-input"
                            @checked(in_array(
                                $permission->name,
                                old('permissions', $userPermissions)
                            ))
                        >

                        <span>{{ $permission->name }}</span>
                    </label>
                </div>
            @empty
                <div class="col-12">
                    <p class="text-warning">
                        No permissions found. Please create permissions first.
                    </p>
                </div>
            @endforelse
        </div>

        @error('permissions')
            <div class="text-danger mt-2">{{ $message }}</div>
        @enderror

        @error('permissions.*')
            <div class="text-danger mt-2">{{ $message }}</div>
        @enderror
    </div>

    {{-- ACTIONS --}}
    <div class="d-flex justify-content-end gap-2 mb-4">
        <a href="{{ route('users.index') }}" class="btn btn-light">
            Cancel
        </a>

        <button type="submit" class="btn btn-primary">
            Save Changes
        </button>
    </div>
</form>

@endsection

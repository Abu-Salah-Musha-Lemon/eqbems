<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::query()->latest()->paginate(15);

        return view('users.index', compact('users'));
    }

    public function create(): View
    {
        return view('users.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return redirect()
            ->route('users.index')
            ->with('success', 'User created successfully.');
    }

    public function show(User $user): View
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user): View
    {
        $roles = Role::where('guard_name', 'web')
            ->orderBy('name')
            ->get();

        $permissions = Permission::where('guard_name', 'web')
            ->orderBy('name')
            ->get();

        $userRoles = $user->roles->pluck('name')->toArray();

        $userPermissions = $user->getDirectPermissions()
            ->pluck('name')
            ->toArray();

        return view('users.edit', compact(
            'user',
            'roles',
            'permissions',
            'userRoles',
            'userPermissions'
        ));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],

            'roles' => ['nullable', 'array'],
            'roles.*' => [
                'string',
                Rule::exists('roles', 'name')
                    ->where('guard_name', 'web'),
            ],

            'permissions' => ['nullable', 'array'],
            'permissions.*' => [
                'string',
                Rule::exists('permissions', 'name')
                    ->where('guard_name', 'web'),
            ],
        ]);

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            ...(
                isset($data['password'])
                    ? ['password' => $data['password']]
                    : []
            ),
        ]);

        // Assign the checked roles.
        $user->syncRoles($data['roles'] ?? []);

        // Assign the checked direct permissions.
        $user->syncPermissions($data['permissions'] ?? []);

        return redirect()
            ->route('users.index')
            ->with('success', 'User, roles, and permissions updated successfully.');
    }

    public function destroy(User $user): RedirectResponse
    {
        abort_if(
            $user->is(auth()->user()),
            422,
            'You cannot delete your own account.'
        );

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'User deleted successfully.');
    }
}

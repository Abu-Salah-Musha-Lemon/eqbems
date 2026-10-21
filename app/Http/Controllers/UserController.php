<?php
namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Institution;
use App\Models\OnlineExamAttempt;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;
class UserController extends Controller
{
    public function index(): View { return view('users.index', ['users' => User::latest()->paginate(15)]); }
    public function create(): View
    {
        return view('users.create', ['roles' => Role::orderBy('name')->get(), 'institutions' => Institution::where('status',true)->orderBy('name')->get()]);
    }
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateUser($request);
        DB::transaction(function () use ($data) {
            $user = User::create(collect($data)->only(['name','email','password','institution_id'])->all());
            $user->syncRoles($data['roles'] ?? []);
        });
        return redirect()->route('users.index')->with('success', 'User created.');
    }
    public function show(User $user): View { return view('users.show', compact('user')); }
    public function edit(User $user): View
    {
        return view('users.edit', [
            'user' => $user,
            'roles' => Role::orderBy('name')->get(),
            'institutions' => Institution::where('status',true)->orderBy('name')->get(),
            'userRoles' => $user->getRoleNames()->all(),
        ]);
    }
    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $this->validateUser($request, $user);
        if (($user->results()->exists() || OnlineExamAttempt::where('student_id', $user->id)->exists())
            && (int) ($data['institution_id'] ?? 0) !== (int) ($user->institution_id ?? 0)) {
            return back()->withErrors(['institution_id' => 'Cannot move a student with exam attempts or recorded results to a different institution.']);
        }
        if ($user->is($request->user()) && ! in_array('Super Admin', $data['roles'] ?? [], true)) {
            return back()->withErrors(['roles' => 'You cannot remove your own Super Admin access.']);
        }
        DB::transaction(function () use ($user, $data) {
            $update = collect($data)->only(['name','email','institution_id'])->all();
            if (! empty($data['password'])) $update['password'] = $data['password'];
            $user->update($update);
            $user->syncRoles($data['roles'] ?? []);
            $user->syncPermissions([]);
        });
        return redirect()->route('users.index')->with('success', 'User and roles updated.');
    }
    public function destroy(User $user): RedirectResponse
    {
        abort_if($user->is(auth()->user()), 422, 'You cannot delete your own account.');
        abort_if($user->hasRole('Super Admin') && User::role('Super Admin')->count() <= 1, 422, 'Cannot delete the last Super Admin.');
        // Preserve historical actor references via nullOnDelete FKs; results use restrictOnDelete.
        abort_if($user->results()->exists() || OnlineExamAttempt::where('student_id', $user->id)->exists(),
            422, 'This account has academic results or online exam attempts.');
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted.');
    }
    private function validateUser(Request $request, ?User $user = null): array
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255', Rule::unique('users','email')->ignore($user?->id)],
            'password' => [$user ? 'nullable' : 'required','string','min:8','confirmed'],
            'institution_id' => ['nullable','integer',Rule::exists('institutions','id')->whereNull('deleted_at')],
            'roles' => ['required','array','min:1'],
            'roles.*' => ['string', Rule::exists('roles','name')->where('guard_name','web')],
        ]);
        if (! in_array('Super Admin', $data['roles'], true) && empty($data['institution_id'])) {
            throw \Illuminate\Validation\ValidationException::withMessages(['institution_id' => 'A non-platform user needs an institution.']);
        }
        if (in_array('Super Admin', $data['roles'], true)) $data['institution_id'] = null;
        return $data;
    }
}

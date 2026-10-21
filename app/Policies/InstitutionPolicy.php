<?php
namespace App\Policies;
use App\Models\Institution;
use App\Models\User;
use App\Support\AccessScope;
class InstitutionPolicy
{
    public function viewAny(User $user): bool { return $user->can('institution.view'); }
    public function view(User $user, Institution $institution): bool { return $user->can('institution.view') && AccessScope::allows($user, $institution->id); }
    public function create(User $user): bool { return AccessScope::isGlobal($user) && $user->can('institution.create'); }
    public function update(User $user, Institution $institution): bool { return AccessScope::isGlobal($user) && $user->can('institution.update'); }
    public function delete(User $user, Institution $institution): bool { return AccessScope::isGlobal($user) && $user->can('institution.delete'); }
    public function restore(User $user, Institution $institution): bool { return AccessScope::isGlobal($user) && $user->can('institution.restore'); }
    public function forceDelete(User $user, Institution $institution): bool { return false; }
}

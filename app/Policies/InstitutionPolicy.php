<?php

namespace App\Policies;

use App\Models\Institution;
use App\Models\User;

class InstitutionPolicy
{
    /**
     * View Institution List
     */
    public function viewAny(User $user): bool
    {
        return $user->can('institution.view');
    }

    /**
     * View Single Institution
     */
    public function view(User $user, Institution $institution): bool
    {
        return $user->can('institution.view');
    }

    /**
     * Create Institution
     */
    public function create(User $user): bool
    {
        return $user->can('institution.create');
    }

    /**
     * Update Institution
     */
    public function update(User $user, Institution $institution): bool
    {
        return $user->can('institution.update');
    }

    /**
     * Delete Institution
     */
    public function delete(User $user, Institution $institution): bool
    {
        return $user->can('institution.delete');
    }

    /**
     * Restore Institution
     */
    public function restore(User $user, Institution $institution): bool
    {
        return $user->can('institution.restore');
    }

    /**
     * Force Delete
     */
    public function forceDelete(User $user, Institution $institution): bool
    {
        return false;
    }
}
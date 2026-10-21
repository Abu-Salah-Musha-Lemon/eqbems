<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

final class AccessScope
{
    public static function isGlobal(User $user): bool
    {
        return $user->hasRole('Super Admin');
    }

    public static function allows(User $user, int|string|null $institutionId): bool
    {
        return $institutionId !== null && (
            self::isGlobal($user) || ((int) $user->institution_id === (int) $institutionId && $user->institution_id !== null)
        );
    }

    public static function restrict(Builder $query, User $user, string $column = 'institution_id'): Builder
    {
        if (! self::isGlobal($user)) {
            $query->where($column, $user->institution_id ?? -1);
        }

        return $query;
    }
}

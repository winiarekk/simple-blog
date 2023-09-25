<?php

namespace App\Infrastructure\Laravel\Policies;

use App\Domain\User\ValueObjects\Role;
use App\Infrastructure\Laravel\Models\UserAccess;

class UserPolicy
{
    public function login(UserAccess $userAccess): bool
    {
        return count(array_intersect(
            array_map(fn(Role $role) => $role->name, $userAccess->roles),
            [Role::ROLE_EDITOR, Role::ROLE_ADMIN]
        )) > 0;
    }

    public function manageUsers(UserAccess $userAccess): bool
    {
        return in_array(Role::ROLE_ADMIN, array_map(fn(Role $role) => $role->name, $userAccess->roles));
    }
}

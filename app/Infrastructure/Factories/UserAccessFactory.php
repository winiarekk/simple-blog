<?php

namespace App\Infrastructure\Factories;

use App\Domain\User\Entities\User;
use App\Domain\User\ValueObjects\Role;
use App\Infrastructure\Laravel\Models\UserAccess;

class UserAccessFactory
{
    /**
     * @param Role[] $roles
     */
    public function create(User $user, array $roles): UserAccess
    {
        $preparedRoles = [];

        foreach ($roles as $role) {
            $preparedRoles[$role->id] = $role;
        }

        return new UserAccess(
            user: $user,
            roles: $preparedRoles
        );
    }
}

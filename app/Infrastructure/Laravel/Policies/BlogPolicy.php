<?php

namespace App\Infrastructure\Laravel\Policies;

use App\Domain\User\ValueObjects\Role;
use App\Infrastructure\Laravel\Models\UserAccess;

class BlogPolicy
{
    public function managePosts(UserAccess $userAccess): bool
    {
        return count(array_intersect(
            array_map(fn(Role $role) => $role->name, $userAccess->roles),
            [Role::ROLE_EDITOR, Role::ROLE_ADMIN]
        )) > 0;
    }
}

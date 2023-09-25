<?php

namespace App\Domain\User\Factories;

use App\Domain\User\ValueObjects\Role;

class RoleFactory
{
    public function createFromRaw(\stdClass $object): Role
    {
        return new Role(
            id: $object->id,
            name: $object->name
        );
    }
}

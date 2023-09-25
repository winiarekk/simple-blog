<?php

namespace App\Domain\User\Factories;

use App\Domain\User\Entities\User;

class UserFactory
{
    public function createFromRaw(\stdClass $object): User
    {
        return new User(
            id: $object->id,
            name: $object->name,
            email: $object->email,
            passwordHash: $object->password
        );
    }
}

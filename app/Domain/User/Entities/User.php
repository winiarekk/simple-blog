<?php

namespace App\Domain\User\Entities;

class User
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
        public string $passwordHash,
    )
    {}
}

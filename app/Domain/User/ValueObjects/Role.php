<?php

namespace App\Domain\User\ValueObjects;

use App\Domain\Shared\Exceptions\VOException;

readonly class Role
{
    public const ROLE_USER = 'User';
    public const ROLE_EDITOR = 'Editor';
    public const ROLE_ADMIN = 'Admin';

    public const ROLES = [
        self::ROLE_USER,
        self::ROLE_EDITOR,
        self::ROLE_ADMIN,
    ];

    public function __construct(
        public int $id,
        public string $name,
    ) {
        if (!in_array($this->name, static::ROLES)) {
            throw new VOException("Invalid value provided for role name!");
        }
    }
}

<?php

namespace App\Domain\User\Repositories;

interface RoleRepositoryInterface
{
    public function getDefaults(): array;

    public function getForUser(int $userId): array;

    public function saveForUser(int $userId, array $roleNames): void;
}

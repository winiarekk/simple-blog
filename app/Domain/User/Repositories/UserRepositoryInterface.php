<?php

namespace App\Domain\User\Repositories;

use App\Domain\User\Entities\User;

interface UserRepositoryInterface
{
    public function getAll(): array;

    public function get(int $id): ?User;

    public function getByEmail(string $email): ?User;

    public function create(string $name, string $email, string $passwordHash): int;

    public function update(User $user): void;

    public function delete(string $id): void;
}

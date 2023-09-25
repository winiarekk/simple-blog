<?php

namespace App\Domain\User\Services;

use App\Domain\User\Repositories\RoleRepositoryInterface;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\User\Services\Interfaces\HashServiceInterface;

class CreateUserService
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly RoleRepositoryInterface $roleRepository,
        private readonly HashServiceInterface $hashService,
    )
    {}

    public function execute(string $name, string $email, string $password): int
    {
        $userId = $this->userRepository->create(
            name: $name,
            email: $email,
            passwordHash: $this->hashService->create($password),
        );

        $this->roleRepository->saveForUser($userId, $this->roleRepository->getDefaults());

        return $userId;
    }
}

<?php

namespace App\Infrastructure\Laravel\Providers;

use App\Domain\User\Repositories\RoleRepositoryInterface;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Infrastructure\Factories\UserAccessFactory;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Hashing\Hasher;

class RepositoryUserProvider implements UserProvider
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly RoleRepositoryInterface $roleRepository,
        private readonly UserAccessFactory $factory,
        private readonly Hasher $hasher
    )
    {}

    public function retrieveById($identifier)
    {
        $user = $this->userRepository->getByEmail($identifier);

        return $this->factory->create(
            $user,
            $this->roleRepository->getForUser($user->id)
        );
    }

    public function retrieveByToken($identifier, $token)
    {
        // Not needed
    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
        // Not needed
    }

    public function retrieveByCredentials(array $credentials)
    {
        $user = $this->userRepository->getByEmail($credentials['email']);

        return $user !== null ? $this->factory->create($user, $this->roleRepository->getForUser($user->id)) : null;
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        return $this->hasher->check($credentials['password'], $user->getAuthPassword());
    }

}

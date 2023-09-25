<?php

namespace App\Infrastructure\Factories;

use App\Domain\Blog\Factories\PostFileFactory;
use App\Domain\User\Factories\RoleFactory;
use App\Domain\User\Factories\UserFactory;
use App\Domain\Blog\Factories\PostFactory;
use App\Infrastructure\Repositories\PostFileRepository;
use App\Infrastructure\Repositories\PostRepository;
use App\Infrastructure\Repositories\RoleRepository;
use App\Infrastructure\Repositories\UserRepository;

class RepositoryFactory
{
    public function createUserRepository(): UserRepository
    {
        return new UserRepository(new UserFactory());
    }

    public function createRoleRepository(): RoleRepository
    {
        return new RoleRepository(new RoleFactory());
    }

    public function createPostRepository(): PostRepository
    {
        return new PostRepository(new PostFactory());
    }

    public function createPostFileRepository(): PostFileRepository
    {
        return new PostFileRepository(new PostFileFactory());
    }
}

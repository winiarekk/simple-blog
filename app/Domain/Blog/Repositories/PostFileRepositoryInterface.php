<?php

namespace App\Domain\Blog\Repositories;

interface PostFileRepositoryInterface
{
    public function getForPosts(array $postIds): array;

    public function saveForPost(int $postId, array $postFiles): void;
}

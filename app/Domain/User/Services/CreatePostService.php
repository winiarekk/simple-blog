<?php

namespace App\Domain\User\Services;

use App\Domain\Blog\Repositories\PostFileRepositoryInterface;
use App\Domain\Blog\Repositories\PostRepositoryInterface;

class CreatePostService
{
    public function __construct(
        private readonly PostRepositoryInterface $postRepository,
        private readonly PostFileRepositoryInterface $postFileRepository,
    )
    {}

    public function execute(string $body, array $files): int
    {
        $postId = $this->postRepository->create($body);

        $this->postFileRepository->saveForPost($postId, $files);

        return $postId;
    }
}

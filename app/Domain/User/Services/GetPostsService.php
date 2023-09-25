<?php

namespace App\Domain\User\Services;


use App\Domain\Blog\Entities\Post;
use App\Domain\Blog\Repositories\PostFileRepositoryInterface;
use App\Domain\Blog\Repositories\PostRepositoryInterface;

class GetPostsService
{
    public function __construct(
        private readonly PostRepositoryInterface $postRepository,
        private readonly PostFileRepositoryInterface $postFileRepository,
    )
    {}

    public function execute(int $page, int $pageSize): array
    {
        $posts = $this->postRepository->getManyPaginated($page, $pageSize);
        $files = $this->postFileRepository->getForPosts(array_map(fn(Post $post) => $post->id, $posts));
        $filesByPost = [];

        foreach ($files as $file) {
            $filesByPost[$file->postId][] = $file;
        }

        return array_map(
            fn(Post $post) => $post->withFiles($filesByPost[$post->id] ?? []),
            $posts
        );
    }
}

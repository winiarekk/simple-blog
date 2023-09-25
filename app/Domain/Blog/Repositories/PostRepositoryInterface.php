<?php

namespace App\Domain\Blog\Repositories;

use App\Domain\Blog\Entities\Post;

interface PostRepositoryInterface
{
    public function getManyPaginated(int $page, int $pageSize): array;

    public function get(int $id): ?Post;

    public function create(string $body): int;

    public function update(Post $post): void;

    public function delete(int $id): void;
}

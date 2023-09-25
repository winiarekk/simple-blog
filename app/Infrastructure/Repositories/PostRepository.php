<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Blog\Entities\Post;
use App\Domain\Blog\Factories\PostFactory;
use App\Domain\Blog\Repositories\PostRepositoryInterface;
use Illuminate\Support\Facades\DB;

class PostRepository implements PostRepositoryInterface
{
    public const TABLE_NAME = 'posts';

    public function __construct(
        private readonly PostFactory $factory
    )
    {}

    public function getManyPaginated(int $page, int $pageSize): array
    {
        return array_map(
            fn(\stdClass $object) => $this->factory->createFromRaw($object),
            DB::table(static::TABLE_NAME)
                ->offset(($page - 1) * $pageSize)
                ->limit($pageSize)
                ->get()
                ->toArray()
        );
    }

    public function get(int $id): ?Post
    {
        $raw = DB::table(static::TABLE_NAME)
            ->where('id', $id)
            ->first();

        return $raw ? $this->factory->createFromRaw($raw) : null;
    }

    public function create(string $body): int
    {
        return DB::table(static::TABLE_NAME)
            ->insertGetId([
                'body' => $body,
            ]);
    }

    public function update(Post $post): void
    {
        DB::table(static::TABLE_NAME)
            ->where('id', $post->id)
            ->update([
                'body' => $post->body,
            ]);
    }

    public function delete(int $id): void
    {
        DB::table(static::TABLE_NAME)
            ->delete($id);
    }
}

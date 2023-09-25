<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Blog\Factories\PostFileFactory;
use App\Domain\Blog\Repositories\PostFileRepositoryInterface;
use Illuminate\Support\Facades\DB;

class PostFileRepository implements PostFileRepositoryInterface
{
    public const TABLE_NAME = 'post_files';

    public function __construct(
        private readonly PostFileFactory $factory,
    )
    {}

    public function getForPosts(array $postIds): array
    {
        return array_map(
            fn(\stdClass $object) => $this->factory->createFromRaw($object),
            DB::table(static::TABLE_NAME)
                ->whereIn('post_id', $postIds)
                ->get()
                ->toArray()
        );
    }

    public function saveForPost(int $postId, array $postFiles): void
    {
        DB::table(static::TABLE_NAME)
            ->where('post_id', $postId)
            ->delete();

        DB::table(static::TABLE_NAME)
            ->insert(array_map(
                fn(array $data) => [
                    'post_id' => $postId,
                    ...$data,
                ],
                $postFiles
            ));
    }
}

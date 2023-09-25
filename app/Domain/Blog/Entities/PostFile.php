<?php

namespace App\Domain\Blog\Entities;

class PostFile
{
    public function __construct(
        public readonly int $postId,
        public readonly string $name,
        public readonly string $path,
    )
    {}
}

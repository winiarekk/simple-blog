<?php

namespace App\Domain\Blog\Entities;

class Post
{
    public function __construct(
        public readonly int $id,
        public string $body,
        public array $files,
    )
    {}

    public function withFiles(array $files): self
    {
        return new static(
            $this->id,
            $this->body,
            $files,
        );
    }
}

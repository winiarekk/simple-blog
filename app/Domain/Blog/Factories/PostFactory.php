<?php

namespace App\Domain\Blog\Factories;

use App\Domain\Blog\Entities\Post;

class PostFactory
{
    public function createFromRaw(\stdClass $object): Post
    {
        return new Post(
            id: $object->id,
            body: $object->body,
            files: []
        );
    }
}

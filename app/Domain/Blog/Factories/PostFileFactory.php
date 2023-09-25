<?php

namespace App\Domain\Blog\Factories;

use App\Domain\Blog\Entities\PostFile;

class PostFileFactory
{
    public function createFromRaw(\stdClass $object): PostFile
    {
        return new PostFile(
            postId: $object->post_id,
            name: $object->name,
            path: $object->path,
        );
    }
}

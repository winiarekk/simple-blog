<?php

namespace App\Infrastructure\Laravel\Controllers;

use App\Domain\Blog\Repositories\PostFileRepositoryInterface;
use App\Domain\Blog\Repositories\PostRepositoryInterface;
use App\Domain\User\Services\CreatePostService;
use App\Domain\User\Services\GetPostsService;
use App\Infrastructure\Factories\RepositoryFactory;
use App\Infrastructure\Laravel\Requests\StorePostRequest;
use App\Infrastructure\Laravel\Requests\UpdatePostRequest;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{
    private const DEFAULT_PAGE_SIZE = 10;

    private PostRepositoryInterface $postRepository;

    private PostFileRepositoryInterface $postFileRepository;

    public function __construct()
    {
        $this->middleware('auth:api')->except(['index']);
        $this->middleware('can:managePosts,App\Domain\Blog\Entities\Post')->except(['index']);
    }

    public function index(int $page): JsonResponse
    {
        return response()->json([
            'items' => (new GetPostsService(
                $this->getPostRepository(),
                $this->getPostFileRepository(),
            ))->execute($page, static::DEFAULT_PAGE_SIZE),
            'pagination' => [
                'page' => $page,
                'limit' => static::DEFAULT_PAGE_SIZE,
            ],
        ]);
    }

    public function store(StorePostRequest $request): JsonResponse
    {
        $files = $request->file('file');
        $filesData = [];

        foreach ($files as $file) {
            $filesData[] = [
                'path' => $file->store('public/files'),
                'name' => $file->getClientOriginalName(),
            ];
        }

        $id = (new CreatePostService(
            $this->getPostRepository(),
            $this->getPostFileRepository(),

        ))->execute(
            body: $request->validated()['body'],
            files: $filesData,
        );

        return response()->json([
            'message' => 'Post created successfully',
            'id' => $id,
        ], 201);
    }

    public function update(UpdatePostRequest $request): JsonResponse
    {
        $post = $this->getPostRepository()->get($request->id);

        if ($post !== null) {
            foreach ($request->validated() as $key => $value) {
                $post->$key = $value;
            }
            $this->getPostRepository()->update($post);

            $response = response()->json([
                'message' => 'Post has been updated',
            ]);
        } else {
            $response = response()->json([
                'message' => 'Post not found',
            ], 404);
        }

        return $response;
    }

    public function delete(int $id): JsonResponse
    {
        $this->getPostRepository()->delete($id);

        return response()->json([
            'message' => 'Post deleted successfully',
        ]);
    }

    private function getPostRepository(): PostRepositoryInterface
    {
        return $this->postRepository ?? ($this->postRepository = (new RepositoryFactory())->createPostRepository());
    }

    private function getPostFileRepository(): PostFileRepositoryInterface
    {
        return $this->postFileRepository ?? ($this->postFileRepository = (new RepositoryFactory())->createPostFileRepository());
    }
}

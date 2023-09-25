<?php

namespace App\Infrastructure\Laravel\Controllers;

use App\Domain\User\Entities\User;
use App\Domain\User\Repositories\RoleRepositoryInterface;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\User\Services\CreateUserService;
use App\Infrastructure\Factories\RepositoryFactory;
use App\Infrastructure\Laravel\Requests\SetRolesRequest;
use App\Infrastructure\Laravel\Requests\StoreUserRequest;
use App\Infrastructure\Laravel\Requests\UpdateUserRequest;
use App\Infrastructure\Services\HashService;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    private UserRepositoryInterface $userRepository;

    private RoleRepositoryInterface $roleRepository;

    public function index(): JsonResponse
    {
        return response()->json([
            'items' => array_map(
                fn(User $user) => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
                $this->getUserRepository()->getAll()
            ),
        ]);
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $id = (new CreateUserService(
            $this->getUserRepository(),
            $this->getRoleRepository(),
            new HashService(),
        ))->execute(
            name: $request->name,
            email: $request->email,
            password: $request->password,
        );

        return response()->json([
            'message' => 'User created successfully',
            'id' => $id,
        ], 201);
    }

    public function update(UpdateUserRequest $request): JsonResponse
    {
        $user = $this->getUserRepository()->get($request->id);

        if ($user !== null) {
            foreach ($request->validated() as $key => $value) {
                $user->$key = $value;
            }
            $this->getUserRepository()->update($user);

            $response = response()->json([
                'message' => 'User has been updated',
            ]);
        } else {
            $response = response()->json([
                'message' => 'User not found',
            ], 404);
        }

        return $response;
    }

    public function delete(int $id): JsonResponse
    {
        $this->getUserRepository()->delete($id);

        return response()->json([
            'message' => 'User deleted successfully',
        ]);
    }

    public function getRoles(int $id): JsonResponse
    {
        return response()->json([
            'items' => $this->getRoleRepository()->getForUser($id),
        ]);
    }

    public function setRoles(int $id, SetRolesRequest $request): JsonResponse
    {
        $this->getRoleRepository()->saveForUser($id, $request->roles);

        return response()->json([
            'message' => 'Roles set successfully',
        ]);
    }

    private function getUserRepository(): UserRepositoryInterface
    {
        return $this->userRepository ?? ($this->userRepository = (new RepositoryFactory())->createUserRepository());
    }

    private function getRoleRepository(): RoleRepositoryInterface
    {
        return $this->roleRepository ?? ($this->roleRepository = (new RepositoryFactory())->createRoleRepository());
    }
}

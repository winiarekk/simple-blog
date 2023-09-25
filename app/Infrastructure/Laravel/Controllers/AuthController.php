<?php

namespace App\Infrastructure\Laravel\Controllers;

use App\Domain\User\Entities\User;
use App\Domain\User\Repositories\RoleRepositoryInterface;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\User\Services\CreateUserService;
use App\Infrastructure\Factories\RepositoryFactory;
use App\Infrastructure\Laravel\Models\UserAccess;
use App\Infrastructure\Laravel\Requests\ForgotPasswordRequest;
use App\Infrastructure\Laravel\Requests\LoginRequest;
use App\Infrastructure\Laravel\Requests\RegisterRequest;
use App\Infrastructure\Laravel\Requests\ResetPasswordRequest;
use App\Infrastructure\Services\HashService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    private UserRepositoryInterface $userRepository;

    private RoleRepositoryInterface $roleRepository;

    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');
        $token = Auth::attempt($credentials);

        $this->authorize('login', User::class);

        if (!$token) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = Auth::user();

        return response()->json([
            'user' => [
                'id' => $user->getAuthIdentifier(),
                'name' => $user->getAuthIdentifierName(),
            ],
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ],
        ]);
    }

    public function register(RegisterRequest $request): JsonResponse
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

    public function logout(): JsonResponse
    {
        Auth::logout();

        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh(): JsonResponse
    {
        $user = Auth::user();

        return response()->json([
            'user' => [
                'id' => $user->getAuthIdentifier(),
                'name' => $user->getAuthIdentifierName(),
            ],
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ],
        ]);
    }

    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        if ($this->getUserRepository()->getByEmail($request->email) !== null) {
            Password::sendResetLink($request->only('email'));
        }

        return response()->json([
            'message' => 'Messsge has been sent if given user email exists.',
        ]);
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (UserAccess $userAccess, string $password) {
                $updatedUser = $userAccess->user;
                $updatedUser->passwordHash = Hash::make($password);

                $this->getUserRepository()->update($updatedUser);
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'message' => 'Password has been changed successfully',
            ]);
        } else {
            return response()->json([
                'message' => __($status),
            ], 422);
        }
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

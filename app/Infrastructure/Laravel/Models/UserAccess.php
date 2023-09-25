<?php

namespace App\Infrastructure\Laravel\Models;

use App\Domain\Shared\Exceptions\InvalidCollectionElementException;
use App\Domain\User\Entities\User;
use App\Domain\User\ValueObjects\Role;
use App\Infrastructure\Laravel\Events\ResetPasswordRequested;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\CanResetPassword;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class UserAccess implements Authenticatable, JWTSubject, CanResetPassword
{
    public function __construct(
        public User $user,
        public array $roles
    )
    {
        foreach ($roles as $key => $role) {
            if (!($role instanceof Role)) {
                throw new InvalidCollectionElementException(sprintf("Expected %s object", Role::class));
            }
            if ($role->id !== $key) {
                throw new InvalidCollectionElementException(sprintf("Element key (%d) doesn't match object ID (%d)", $key, $role->id));
            }
        }
    }

    public function getAuthIdentifierName()
    {
        return $this->user->name;
    }

    public function getAuthIdentifier()
    {
        return $this->user->id;
    }

    public function getAuthPassword()
    {
        return $this->user->passwordHash;
    }

    public function getRememberToken()
    {
        // Not needed
    }

    public function setRememberToken($value)
    {
        // Not needed
    }

    public function getRememberTokenName()
    {
        // Not needed
    }

    public function getJWTIdentifier()
    {
        return $this->user->email;
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function sendPasswordResetNotification($token)
    {
        ResetPasswordRequested::dispatch($this->user->email, $token);
    }

    public function getEmailForPasswordReset()
    {
        return $this->user->email;
    }
}

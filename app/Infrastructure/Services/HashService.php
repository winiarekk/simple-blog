<?php

namespace App\Infrastructure\Services;

use App\Domain\User\Services\Interfaces\HashServiceInterface;
use Illuminate\Support\Facades\Hash;

class HashService implements HashServiceInterface
{
    public function create(string $value): string
    {
        return Hash::make($value);
    }
}

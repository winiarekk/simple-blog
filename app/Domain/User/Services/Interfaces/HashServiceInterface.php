<?php

namespace App\Domain\User\Services\Interfaces;

interface HashServiceInterface
{
    public function create(string $value): string;
}

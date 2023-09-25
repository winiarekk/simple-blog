<?php

namespace App\Infrastructure\Repositories;

use App\Domain\User\Entities\User;
use App\Domain\User\Factories\UserFactory;
use App\Domain\User\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;

class UserRepository implements UserRepositoryInterface
{
    public const TABLE_NAME = 'users';

    public function __construct(
        private readonly UserFactory $factory
    ) {
    }

    public function getAll(): array
    {
        return array_map(
            fn(\stdClass $object) => $this->factory->createFromRaw($object),
            DB::table(static::TABLE_NAME)->get()->toArray()
        );
    }

    public function get(int $id): ?User
    {
        $raw = DB::table(static::TABLE_NAME)
            ->where('id', $id)
            ->first();

        return $raw ? $this->factory->createFromRaw($raw) : null;
    }


    public function getByEmail(string $email): ?User
    {
        $raw = DB::table(static::TABLE_NAME)
            ->where('email', $email)
            ->first();

        return $raw ? $this->factory->createFromRaw($raw) : null;
    }


    public function create(string $name, string $email, string $passwordHash): int
    {
        return DB::table(static::TABLE_NAME)
            ->insertGetId([
                'name' => $name,
                'email' => $email,
                'password' => $passwordHash,
            ]);
    }

    public function update(User $user): void
    {
        DB::table(static::TABLE_NAME)
            ->where('id', $user->id)
            ->update([
                'name' => $user->name,
                'email' => $user->email,
                'password' => $user->passwordHash,
            ]);
    }

    public function delete(string $id): void
    {
        DB::table(static::TABLE_NAME)
            ->delete($id);
    }
}

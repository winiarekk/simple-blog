<?php

namespace App\Infrastructure\Repositories;

use App\Domain\User\Factories\RoleFactory;
use App\Domain\User\Repositories\RoleRepositoryInterface;
use Illuminate\Support\Facades\DB;

class RoleRepository implements RoleRepositoryInterface
{
    public const ROLES_TABLE_NAME = 'roles';

    public const USERS_ROLES_TABLE_NAME = 'users_roles';

    public function __construct(
        private readonly RoleFactory $factory
    ) {
    }

    public function getDefaults(): array
    {
        return array_map(
            fn(\stdClass $object) => $this->factory->createFromRaw($object),
            DB::table(static::ROLES_TABLE_NAME)
                ->where('default', true)
                ->get()
                ->toArray()
        );
    }

    public function getForUser(int $userId): array
    {
        return array_map(
            fn(\stdClass $object) => $this->factory->createFromRaw($object),
            DB::table(static::USERS_ROLES_TABLE_NAME)
                ->join(static::ROLES_TABLE_NAME, static::ROLES_TABLE_NAME . '.id', '=', static::USERS_ROLES_TABLE_NAME . '.role_id')
                ->where(static::USERS_ROLES_TABLE_NAME . '.user_id', $userId)
                ->select(static::ROLES_TABLE_NAME . '.*')
                ->get()
                ->toArray()
        );
    }

    public function saveForUser(int $userId, array $roleNames): void
    {
        DB::table(static::USERS_ROLES_TABLE_NAME)
            ->where('user_id', $userId)
            ->delete();

        $roles = DB::table(static::ROLES_TABLE_NAME)
            ->whereIn('name', $roleNames)
            ->get()
            ->toArray();

        DB::table(static::USERS_ROLES_TABLE_NAME)
            ->insert(array_map(
                fn(\stdClass $object) => [
                    'user_id' => $userId,
                    'role_id' => $object->id,
                ],
                $roles
            ));
    }
}

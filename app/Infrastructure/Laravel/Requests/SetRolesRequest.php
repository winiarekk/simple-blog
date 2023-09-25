<?php

namespace App\Infrastructure\Laravel\Requests;

use App\Domain\User\ValueObjects\Role;
use Illuminate\Validation\Rules\In;

class SetRolesRequest extends ApiRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'roles' => 'required|array',
            'roles.*' => new In(Role::ROLES),
        ];
    }
}

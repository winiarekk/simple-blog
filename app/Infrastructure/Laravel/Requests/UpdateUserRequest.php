<?php

namespace App\Infrastructure\Laravel\Requests;

class UpdateUserRequest extends ApiRequest
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
            'name' => 'string|min:6|max:15',
            'email' => 'string|email|max:255|unique:users',
            'password' => 'string|min:8',
        ];
    }
}

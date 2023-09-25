<?php

namespace App\Infrastructure\Laravel\Requests;

class RegisterRequest extends ApiRequest
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
            'name' => 'required|string|min:6|max:15',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ];
    }
}

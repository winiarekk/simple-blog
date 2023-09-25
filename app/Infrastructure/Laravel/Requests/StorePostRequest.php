<?php

namespace App\Infrastructure\Laravel\Requests;

class StorePostRequest extends ApiRequest
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
            'body' => 'required',
            'file.*' => 'mimes:png,jpg,jpeg,gif|max:2048',
        ];
    }
}

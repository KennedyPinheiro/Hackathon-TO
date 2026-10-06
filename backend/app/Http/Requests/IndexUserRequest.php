<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IndexUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('usuarios.visualizar') ?? false;
    }

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255',],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100',],
            'page' => ['nullable', 'integer', 'min:1',],
        ];
    }
}

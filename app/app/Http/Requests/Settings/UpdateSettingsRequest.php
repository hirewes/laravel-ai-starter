<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->user()?->id),
            ],
            'dark_mode' => ['required', 'boolean'],
            'preferred_model' => ['required', 'string', 'max:100'],
            'preferred_temperature' => ['required', 'numeric', 'between:0,2'],
            'password' => ['nullable', 'confirmed', 'min:8'],
        ];
    }
}

<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin' || $this->user()?->hasRole('admin');
    }

    public function rules(): array
    {
        return [
            'role' => ['required', 'in:teacher,student'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:'.User::class,
            ],
            'phone' => ['nullable', 'string', 'max:50'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ];
    }
}

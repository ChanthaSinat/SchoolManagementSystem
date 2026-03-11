<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreSchoolTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin' || $this->user()?->hasRole('admin');
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:100', 'alpha_dash', 'unique:school_types,code'],
            'year_levels' => ['nullable', 'array'],
            'year_levels.*' => ['string', 'max:255'],
            'is_default' => ['sometimes', 'boolean'],
        ];
    }
}


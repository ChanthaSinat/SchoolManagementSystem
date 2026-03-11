<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSchoolTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin' || $this->user()?->hasRole('admin');
    }

    public function rules(): array
    {
        $schoolTypeId = $this->route('school_type')?->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => [
                'required',
                'string',
                'max:100',
                'alpha_dash',
                Rule::unique('school_types', 'code')->ignore($schoolTypeId),
            ],
            'year_levels' => ['nullable', 'array'],
            'year_levels.*' => ['string', 'max:255'],
            'is_default' => ['sometimes', 'boolean'],
        ];
    }
}


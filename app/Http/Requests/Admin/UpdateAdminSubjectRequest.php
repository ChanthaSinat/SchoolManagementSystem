<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAdminSubjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin' || $this->user()?->hasRole('admin');
    }

    public function rules(): array
    {
        $subjectId = $this->route('subject')?->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('subjects', 'code')->ignore($subjectId),
            ],
            'school_type_id' => ['nullable', 'exists:school_types,id'],
            'year_level' => ['nullable', 'string', 'max:255'],
            'semester_id' => ['nullable', 'exists:semesters,id'],
        ];
    }
}


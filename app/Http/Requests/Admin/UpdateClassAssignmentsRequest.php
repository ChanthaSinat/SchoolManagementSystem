<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClassAssignmentsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->role === 'admin' || $this->user()->hasRole('admin');
    }

    protected function prepareForValidation(): void
    {
        $subjects = $this->input('subjects', []);

        // Filter out subjects where the checkbox was NOT checked (id is missing)
        $subjects = array_filter($subjects, function ($row) {
            return isset($row['id']);
        });

        $subjects = array_values(array_map(function ($row) {
            $row['teacher_id'] = isset($row['teacher_id']) && $row['teacher_id'] !== '' ? (int) $row['teacher_id'] : null;
            return $row;
        }, $subjects));

        $this->merge(['subjects' => $subjects]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'subjects' => ['nullable', 'array'],
            'subjects.*.id' => ['required', 'exists:subjects,id'],
            'subjects.*.teacher_id' => ['nullable', 'exists:users,id'],
            'student_ids' => ['nullable', 'array'],
            'student_ids.*' => ['integer', 'exists:users,id'],
        ];
    }
}

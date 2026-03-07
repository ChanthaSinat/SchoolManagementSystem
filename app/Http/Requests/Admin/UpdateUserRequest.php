<?php

namespace App\Http\Requests\Admin;

use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->route('user');
        return $user && $user->id !== $this->user()->id
            && ($user->role === 'teacher' || $user->role === 'student' || $user->hasRole('teacher') || $user->hasRole('student'));
    }

    public function rules(): array
    {
        $userId = $this->route('user')?->id;
        $user = $this->route('user');
        $isStudent = $user && ($user->role === 'student' || $user->hasRole('student'));
        $isTeacher = $user && ($user->role === 'teacher' || $user->hasRole('teacher'));

        $rules = [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name'  => ['required', 'string', 'max:255'],
            'email'      => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($userId),
            ],
            'phone'      => ['nullable', 'string', 'max:50'],
            'password'   => ['nullable', 'string', 'min:6', 'confirmed'],
        ];

        if ($isStudent) {
            $rules['school_class_id'] = ['nullable', 'exists:school_classes,id'];
            $rules['section_id'] = ['nullable', 'exists:sections,id'];
            $rules['roll_number'] = ['nullable', 'integer', 'min:1'];
            $rules['guardian_phone'] = ['nullable', 'string', 'max:50'];
        }

        if ($isTeacher) {
            $rules['class_ids'] = ['nullable', 'array'];
            $rules['class_ids.*'] = ['integer', 'exists:school_classes,id'];
        }

        return $rules;
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $user = $this->route('user');
            if (! $user || $user->role !== 'student' && ! $user->hasRole('student')) {
                return;
            }
            $classId = $this->input('school_class_id');
            $sectionId = $this->input('section_id');
            if ($classId && $sectionId) {
                $class = SchoolClass::find($classId);
                if ($class && ! $class->sections->contains('id', (int) $sectionId)) {
                    $validator->errors()->add('section_id', __('The selected section does not belong to the selected class.'));
                }
            }
        });
    }
}

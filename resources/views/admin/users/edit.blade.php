<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 tracking-tight">{{ __('Edit :role', ['role' => $roleLabel]) }}</h2>
                <p class="text-gray-500 text-sm mt-1">{{ $user->full_name ?: $user->name }}</p>
            </div>
            <a href="{{ route($listRoute) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-700">{{ __('Back to :list', ['list' => $listLabel]) }}</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            @if (session('error'))
                <div class="mb-6 rounded-xl bg-red-50 border border-red-200 p-4 text-sm font-medium text-red-800">{{ session('error') }}</div>
            @endif

            <div class="bg-white rounded-2xl shadow border border-gray-100 p-6">
                <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label for="first_name" :value="__('First name')" />
                        <x-text-input id="first_name" name="first_name" type="text" class="mt-1 block w-full" :value="old('first_name', $user->first_name)" required autofocus />
                        <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
                    </div>

                    <div>
                        <x-input-label for="last_name" :value="__('Last name')" />
                        <x-text-input id="last_name" name="last_name" type="text" class="mt-1 block w-full" :value="old('last_name', $user->last_name)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('last_name')" />
                    </div>

                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('email')" />
                    </div>

                    <div>
                        <x-input-label for="phone" :value="__('Phone')" />
                        <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $user->phone)" />
                        <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                    </div>

                    <div>
                        <x-input-label for="password" :value="__('New password (leave blank to keep current)')" />
                        <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                        <x-input-error class="mt-2" :messages="$errors->get('password')" />
                    </div>

                    <div>
                        <x-input-label for="password_confirmation" :value="__('Confirm password')" />
                        <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                    </div>

                    @if ($roleLabel === 'Student' && isset($schoolClasses))
                        <div class="pt-6 border-t border-gray-200 space-y-4">
                            <h3 class="text-sm font-semibold text-gray-800">{{ __('Class assignment') }}</h3>
                            <p class="text-xs text-gray-500">{{ __('Assign this student to a class and section. Leave empty to remove from class.') }}</p>
                            <div>
                                <x-input-label for="school_class_id" :value="__('Class')" />
                                <select id="school_class_id" name="school_class_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                    <option value="">{{ __('— Select class —') }}</option>
                                    @foreach ($schoolClasses as $class)
                                        <option value="{{ $class->id }}" data-sections="{{ $class->sections->map(fn($s) => ['id' => $s->id, 'name' => $s->name])->values()->toJson() }}"
                                            {{ old('school_class_id', $enrollment?->school_class_id ?? '') == $class->id ? 'selected' : '' }}>
                                            {{ $class->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('school_class_id')" />
                            </div>
                            <div>
                                <x-input-label for="section_id" :value="__('Section')" />
                                <select id="section_id" name="section_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" data-selected="{{ old('section_id', $enrollment?->section_id ?? '') }}">
                                    <option value="">{{ __('— Select section —') }}</option>
                                    @if ($enrollment && $enrollment->schoolClass)
                                        @foreach ($enrollment->schoolClass->sections as $sec)
                                            <option value="{{ $sec->id }}" {{ old('section_id', $enrollment?->section_id ?? '') == $sec->id ? 'selected' : '' }}>{{ $sec->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('section_id')" />
                            </div>
                            <div>
                                <x-input-label for="roll_number" :value="__('Roll number')" />
                                <x-text-input id="roll_number" name="roll_number" type="number" min="1" class="mt-1 block w-full" :value="old('roll_number', $enrollment?->roll_number ?? '')" />
                                <x-input-error class="mt-2" :messages="$errors->get('roll_number')" />
                            </div>
                            <div>
                                <x-input-label for="guardian_phone" :value="__('Guardian phone')" />
                                <x-text-input id="guardian_phone" name="guardian_phone" type="text" class="mt-1 block w-full" :value="old('guardian_phone', $enrollment?->guardian_phone ?? '')" />
                                <x-input-error class="mt-2" :messages="$errors->get('guardian_phone')" />
                            </div>
                        </div>
                        <script>
                            (function() {
                                var classSelect = document.getElementById('school_class_id');
                                var sectionSelect = document.getElementById('section_id');
                                if (!classSelect || !sectionSelect) return;
                                classSelect.addEventListener('change', function() {
                                    var opt = classSelect.options[classSelect.selectedIndex];
                                    var sections = opt.getAttribute('data-sections') ? JSON.parse(opt.getAttribute('data-sections')) : [];
                                    sectionSelect.innerHTML = '<option value="">{{ __("— Select section —") }}</option>';
                                    sections.forEach(function(s) {
                                        var o = document.createElement('option');
                                        o.value = s.id;
                                        o.textContent = s.name;
                                        sectionSelect.appendChild(o);
                                    });
                                    var selected = sectionSelect.getAttribute('data-selected');
                                    if (selected) sectionSelect.value = selected;
                                });
                                classSelect.dispatchEvent(new Event('change'));
                            })();
                        </script>
                    @endif

                    @if ($roleLabel === 'Teacher' && isset($schoolClasses))
                        <div class="pt-6 border-t border-gray-200 space-y-4">
                            <h3 class="text-sm font-semibold text-gray-800">{{ __('Classes assigned') }}</h3>
                            <p class="text-xs text-gray-500">{{ __('Select which classes this teacher teaches.') }}</p>
                            <div class="space-y-2 max-h-48 overflow-y-auto border border-gray-100 rounded-lg p-3 bg-gray-50/50">
                                @foreach ($schoolClasses as $class)
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" name="class_ids[]" value="{{ $class->id }}" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                            {{ in_array($class->id, $teacherClassIds ?? []) ? 'checked' : '' }} />
                                        <span class="text-sm text-gray-800">{{ $class->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @if ($schoolClasses->isEmpty())
                                <p class="text-sm text-amber-600">{{ __('No classes created yet. Create classes first.') }}</p>
                            @endif
                        </div>
                    @endif

                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                        <x-primary-button>{{ __('Save changes') }}</x-primary-button>
                        <a href="{{ route($listRoute) }}" class="text-sm text-gray-600 hover:text-gray-800">{{ __('Cancel') }}</a>
                    </div>
                </form>

                <div class="mt-8 pt-6 border-t border-gray-200">
                    <p class="text-sm text-gray-600 mb-2">{{ __('Remove this user permanently?') }}</p>
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('This cannot be undone. Remove this user?') }}');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-sm text-red-600 hover:text-red-800 font-medium">{{ __('Remove user') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

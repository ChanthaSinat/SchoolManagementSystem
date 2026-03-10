@extends('layouts.admin-app')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <!-- Header Area -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <a href="{{ route($listRoute) }}" class="p-2 bg-white rounded-xl border border-slate-200 text-slate-400 hover:text-indigo-600 hover:border-indigo-100 transition-all shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                </a>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">{{ __('Edit :role', ['role' => $roleLabel]) }}</h1>
            </div>
            <p class="text-slate-500 font-medium ml-12">{{ $user->full_name ?: $user->name }} • Account Settings</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="px-3 py-1 bg-indigo-50 text-indigo-700 text-[10px] font-black rounded-full border border-indigo-100 uppercase tracking-wider">{{ $roleLabel }} Profile</span>
        </div>
    </div>

    <!-- Alert Notifications -->
    @if (session('error'))
        <div class="bg-rose-50 border border-rose-200 text-rose-800 px-6 py-4 rounded-2xl flex items-center gap-3 shadow-sm animate-in fade-in slide-in-from-top-4 duration-300">
            <div class="bg-rose-500 text-white p-1 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
            </div>
            <p class="text-sm font-bold">{{ session('error') }}</p>
        </div>
    @endif

    <!-- Main Form Container -->
    <div class="bg-white/60 backdrop-blur-xl rounded-3xl border border-white shadow-xl shadow-slate-200/50 overflow-hidden">
        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="divide-y divide-slate-100">
            @csrf
            @method('PUT')

            <!-- Personal Information Section -->
            <div class="p-8 space-y-6">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">Personal Information</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <x-input-label for="first_name" :value="__('First Name')" class="text-xs font-black uppercase tracking-wider text-slate-500 ml-1" />
                        <x-text-input id="first_name" name="first_name" type="text" class="w-full px-4 py-3 bg-white/50 border-slate-200 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-400 transition-all" :value="old('first_name', $user->first_name)" required autofocus />
                        <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
                    </div>

                    <div class="space-y-2">
                        <x-input-label for="last_name" :value="__('Last Name')" class="text-xs font-black uppercase tracking-wider text-slate-500 ml-1" />
                        <x-text-input id="last_name" name="last_name" type="text" class="w-full px-4 py-3 bg-white/50 border-slate-200 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-400 transition-all" :value="old('last_name', $user->last_name)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('last_name')" />
                    </div>

                    <div class="space-y-2">
                        <x-input-label for="email" :value="__('Email Address')" class="text-xs font-black uppercase tracking-wider text-slate-500 ml-1" />
                        <x-text-input id="email" name="email" type="email" class="w-full px-4 py-3 bg-white/50 border-slate-200 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-400 transition-all" :value="old('email', $user->email)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('email')" />
                    </div>

                    <div class="space-y-2">
                        <x-input-label for="phone" :value="__('Phone Number')" class="text-xs font-black uppercase tracking-wider text-slate-500 ml-1" />
                        <x-text-input id="phone" name="phone" type="text" class="w-full px-4 py-3 bg-white/50 border-slate-200 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-400 transition-all" :value="old('phone', $user->phone)" />
                        <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                    </div>
                </div>
            </div>

            <!-- Security Section -->
            <div class="p-8 space-y-6 bg-slate-50/30">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-8 h-8 rounded-lg bg-amber-500 flex items-center justify-center text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">Account Security</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <x-input-label for="password" :value="__('New Password')" class="text-xs font-black uppercase tracking-wider text-slate-500 ml-1" />
                        <x-text-input id="password" name="password" type="password" class="w-full px-4 py-3 bg-white/50 border-slate-200 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-400 transition-all" placeholder="Leave blank to keep current" autocomplete="new-password" />
                        <x-input-error class="mt-2" :messages="$errors->get('password')" />
                    </div>

                    <div class="space-y-2">
                        <x-input-label for="password_confirmation" :value="__('Confirm New Password')" class="text-xs font-black uppercase tracking-wider text-slate-500 ml-1" />
                        <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="w-full px-4 py-3 bg-white/50 border-slate-200 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-400 transition-all" autocomplete="new-password" />
                    </div>
                </div>
            </div>

            <!-- Assignment Section -->
            @if (isset($schoolClasses))
                <div class="p-8 space-y-6">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-emerald-600 flex items-center justify-center text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/><path d="M6.5 18H20"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800">{{ $roleLabel === 'Student' ? 'Class Enrollment' : 'Class Assignment' }}</h3>
                    </div>

                    @if ($roleLabel === 'Student')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <x-input-label for="year_level_student" :value="__('Year Level')" class="text-xs font-black uppercase tracking-wider text-slate-500 ml-1" />
                                <select id="year_level_student" class="w-full px-4 py-3 bg-white/50 border-slate-200 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-400 transition-all text-sm outline-none">
                                    <option value="">{{ __('— Select Year —') }}</option>
                                    <option value="foundation">Foundation</option>
                                    <option value="sophomore">Sophomore</option>
                                    <option value="junior">Junior</option>
                                    <option value="senior">Senior</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <x-input-label for="semester_student" :value="__('Semester')" class="text-xs font-black uppercase tracking-wider text-slate-500 ml-1" />
                                <select id="semester_student" class="w-full px-4 py-3 bg-white/50 border-slate-200 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-400 transition-all text-sm outline-none">
                                    <option value="">{{ __('— Select Semester —') }}</option>
                                    <option value="1">Semester 1</option>
                                    <option value="2">Semester 2</option>
                                </select>
                            </div>
                            <div class="space-y-2 md:col-span-2">
                                <x-input-label for="school_class_id" :value="__('Select Class')" class="text-xs font-black uppercase tracking-wider text-slate-500 ml-1" />
                                <select id="school_class_id" name="school_class_id" class="w-full px-4 py-3 bg-white/50 border-slate-200 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-400 transition-all text-sm outline-none">
                                    <option value="">{{ __('— None —') }}</option>
                                    @foreach ($schoolClasses as $class)
                                        <option value="{{ $class->id }}"
                                            data-year="{{ $class->year_level ?? 'foundation' }}"
                                            data-semester="{{ $class->semester ?? 1 }}"
                                            data-sections="{{ $class->sections->map(fn($s) => ['id' => $s->id, 'name' => $s->name])->values()->toJson() }}"
                                            {{ old('school_class_id', $enrollment?->school_class_id ?? '') == $class->id ? 'selected' : '' }}>
                                            {{ $class->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Section is now auto-assigned based on class; no manual A/B selection -->
                            <input type="hidden" id="section_id" name="section_id" value="{{ old('section_id', $enrollment?->section_id ?? '') }}">
                            <div class="space-y-2">
                                <x-input-label for="roll_number" :value="__('Roll Number')" class="text-xs font-black uppercase tracking-wider text-slate-500 ml-1" />
                                <x-text-input id="roll_number" name="roll_number" type="number" class="w-full px-4 py-3 bg-white/50 border-slate-200 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-400 transition-all" :value="old('roll_number', $enrollment?->roll_number ?? '')" />
                            </div>
                            <div class="space-y-2">
                                <x-input-label for="guardian_phone" :value="__('Guardian Phone')" class="text-xs font-black uppercase tracking-wider text-slate-500 ml-1" />
                                <x-text-input id="guardian_phone" name="guardian_phone" type="text" class="w-full px-4 py-3 bg-white/50 border-slate-200 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-400 transition-all" :value="old('guardian_phone', $enrollment?->guardian_phone ?? '')" />
                            </div>
                        </div>
                    @else
                        <div class="space-y-4">
                            <p class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1 mb-2">Assigned Classes</p>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="space-y-2 md:col-span-1">
                                    <x-input-label for="year_level_teacher" :value="__('Year Level')" class="text-xs font-black uppercase tracking-wider text-slate-500 ml-1" />
                                    <select id="year_level_teacher" class="w-full px-4 py-3 bg-white/50 border-slate-200 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-400 transition-all text-sm outline-none">
                                        <option value="">{{ __('— All Years —') }}</option>
                                        <option value="foundation">Foundation</option>
                                        <option value="sophomore">Sophomore</option>
                                        <option value="junior">Junior</option>
                                        <option value="senior">Senior</option>
                                    </select>
                                </div>
                                <div class="space-y-2 md:col-span-1">
                                    <x-input-label for="semester_teacher" :value="__('Semester')" class="text-xs font-black uppercase tracking-wider text-slate-500 ml-1" />
                                    <select id="semester_teacher" class="w-full px-4 py-3 bg-white/50 border-slate-200 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-400 transition-all text-sm outline-none">
                                        <option value="">{{ __('— All Semesters —') }}</option>
                                        <option value="1">Semester 1</option>
                                        <option value="2">Semester 2</option>
                                    </select>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 p-4 bg-slate-50/50 rounded-2xl border border-slate-100 max-h-56 overflow-y-auto custom-scrollbar" id="teacher-class-list">
                                @foreach ($schoolClasses as $class)
                                    <label class="group flex items-center gap-3 p-3 bg-white border border-slate-200 rounded-xl cursor-pointer hover:border-indigo-400 hover:bg-indigo-50/50 transition-all shadow-sm"
                                           data-year="{{ $class->year_level ?? 'foundation' }}"
                                           data-semester="{{ $class->semester ?? 1 }}">
                                        <input type="checkbox" name="class_ids[]" value="{{ $class->id }}" class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500/20"
                                            {{ in_array($class->id, $teacherClassIds ?? []) ? 'checked' : '' }} />
                                        <span class="text-sm font-bold text-slate-700 group-hover:text-indigo-700 transition-colors">
                                            {{ $class->name }}
                                            @if($class->year_level || $class->semester)
                                                <span class="block text-[10px] font-semibold text-slate-400 mt-0.5">
                                                    {{ ucfirst($class->year_level ?? 'foundation') }} • {{ $class->semester ? 'Sem '.$class->semester : 'Sem 1' }}
                                                </span>
                                            @endif
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                            @if ($schoolClasses->isEmpty())
                                <div class="p-6 text-center bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                                    <p class="text-sm font-medium text-slate-400">No classes available in the system.</p>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            @endif

            <!-- Form Actions -->
            <div class="p-8 bg-slate-50/50 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-4 w-full sm:w-auto">
                    <button type="submit" class="w-full sm:w-auto px-8 py-3.5 bg-indigo-600 text-white rounded-xl text-sm font-black hover:bg-indigo-700 shadow-lg shadow-indigo-600/20 active:scale-95 transition-all">
                        Save Profile Changes
                    </button>
                    <a href="{{ route($listRoute) }}" class="text-sm font-bold text-slate-400 hover:text-slate-600 px-4 transition-colors">Discard</a>
                </div>
            </div>
        </form>
    </div>

    <!-- Danger Zone -->
    <div class="relative group">
        <div class="absolute -inset-1 bg-gradient-to-r from-rose-500/20 to-orange-500/20 rounded-[2rem] blur opacity-0 group-hover:opacity-100 transition duration-1000"></div>
        <div class="relative bg-white/60 backdrop-blur-xl rounded-3xl border border-rose-100 overflow-hidden shadow-sm">
            <div class="p-8 flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-rose-50 flex items-center justify-center text-rose-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                    </div>
                    <div>
                        <h4 class="text-lg font-black text-slate-900 tracking-tight">Danger Zone</h4>
                        <p class="text-sm font-medium text-slate-500">Permanently remove this user and all associated data from the system.</p>
                    </div>
                </div>
                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="w-full md:w-auto" onsubmit="return confirm('{{ __('This action is irreversible. Are you sure you want to remove this user?') }}');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full md:w-auto px-6 py-3 bg-white text-rose-600 border border-rose-200 rounded-xl text-sm font-bold hover:bg-rose-600 hover:text-white hover:border-rose-600 transition-all active:scale-95 shadow-sm">
                        Remove User Account
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@if (isset($schoolClasses))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Shared elements
        var classSelect = document.getElementById('school_class_id');
        var sectionSelect = document.getElementById('section_id');

        // Student: filter single class select by year & semester using data attributes
        var yearStudent = document.getElementById('year_level_student');
        var semStudent = document.getElementById('semester_student');
        var classOptions = classSelect ? Array.from(classSelect.options) : [];

        function applyStudentFilters() {
            if (!classSelect) return;

            var year = yearStudent ? yearStudent.value : '';
            var sem = semStudent ? semStudent.value : '';

            classOptions.forEach(function(opt) {
                // Always show the placeholder option
                if (!opt.value) {
                    opt.hidden = false;
                    return;
                }

                var cYear = opt.getAttribute('data-year') || '';
                var cSem = opt.getAttribute('data-semester') || '';

                var show = true;
                if (year && cYear !== year) show = false;
                if (sem && String(cSem) !== String(sem)) show = false;

                opt.hidden = !show;
            });

            // If current selection is hidden by filters, reset to none
            var selected = classSelect.options[classSelect.selectedIndex];
            if (!selected || selected.hidden) {
                classSelect.value = '';
            }

            updateSections();
        }

        function updateSections() {
            if (!classSelect || !sectionSelect) return;
            var opt = classSelect.options[classSelect.selectedIndex];
            var sections = opt && opt.dataset.sections
                ? JSON.parse(opt.dataset.sections)
                : [];

            if (sections.length > 0) {
                sectionSelect.value = sections[0].id;
            } else {
                sectionSelect.value = '';
            }
        }

        if (yearStudent && semStudent) {
            yearStudent.addEventListener('change', applyStudentFilters);
            semStudent.addEventListener('change', applyStudentFilters);
        }
        if (classSelect) {
            classSelect.addEventListener('change', updateSections);
        }

        // Teacher: filter checkbox grid
        var yearTeacher = document.getElementById('year_level_teacher');
        var semTeacher = document.getElementById('semester_teacher');
        var classCards = document.querySelectorAll('#teacher-class-list label[data-year][data-semester]');

        function filterTeacherClasses() {
            if (!yearTeacher || !semTeacher) return;
            var year = yearTeacher.value;
            var sem = semTeacher.value;

            classCards.forEach(function(card) {
                var cYear = card.getAttribute('data-year');
                var cSem = card.getAttribute('data-semester');

                var show = true;
                if (year && cYear !== year) show = false;
                if (sem && String(cSem) !== String(sem)) show = false;

                card.style.display = show ? '' : 'none';
            });
        }

        if (yearTeacher && semTeacher) {
            yearTeacher.addEventListener('change', filterTeacherClasses);
            semTeacher.addEventListener('change', filterTeacherClasses);
        }

        // Initial setup
        filterTeacherClasses();

        // Pre-fill student year/semester from currently selected class, if any
        if (classSelect && yearStudent && semStudent) {
            var currentOpt = classSelect.options[classSelect.selectedIndex];
            if (currentOpt) {
                var currentYear = currentOpt.getAttribute('data-year');
                var currentSem = currentOpt.getAttribute('data-semester');
                if (currentYear) yearStudent.value = currentYear;
                if (currentSem) semStudent.value = String(currentSem);
            }
        }

        applyStudentFilters();
    });
</script>
@endif
@endsection

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $schoolClass->name }} — {{ $section->name }} | {{ \Carbon\Carbon::parse($date)->format('M j, Y') }}
            @if($existing->isNotEmpty())
                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-800">{{ __('Edit') }}</span>
            @endif
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <a href="{{ route('teacher.attendance.index') }}" class="text-gray-600 hover:text-gray-900 text-sm">{{ __('Cancel') }}</a>
                        <button type="button" id="mark-all-present" class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded text-white bg-green-600 hover:bg-green-700">
                            {{ __('Mark All Present') }}
                        </button>
                    </div>

                    <form action="{{ route('teacher.attendance.store') }}" method="POST" id="attendance-form">
                        @csrf
                        <input type="hidden" name="class_id" value="{{ $classId }}">
                        <input type="hidden" name="section_id" value="{{ $sectionId }}">
                        <input type="hidden" name="date" value="{{ $date }}">

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase w-14">{{ __('') }}</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Student') }}</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Status') }}</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase min-w-[140px]">{{ __('Note') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @php
                                        $colors = ['bg-indigo-500','bg-green-500','bg-amber-500','bg-red-500','bg-blue-500','bg-purple-500','bg-teal-500','bg-pink-500'];
                                    @endphp
                                    @foreach ($students as $enrollment)
                                        @php
                                            $sid = $enrollment->student_id;
                                            $user = $enrollment->user;
                                            $existingRecord = $existing->get($sid);
                                            $status = $existingRecord ? $existingRecord->status : 'present';
                                            $note = $existingRecord ? ($existingRecord->note ?? '') : '';
                                            $initials = ($user ? mb_substr($user->first_name ?? '', 0, 1) . mb_substr($user->last_name ?? '', 0, 1) : '??');
                                            $colorIndex = ord(mb_strtoupper(mb_substr($initials, 0, 1))) % count($colors);
                                            $bgColor = $colors[$colorIndex];
                                        @endphp
                                        <tr class="attendance-row" data-student-id="{{ $sid }}">
                                            <td class="px-4 py-3">
                                                <div class="w-10 h-10 rounded-full {{ $bgColor }} flex items-center justify-center text-white text-sm font-medium" title="{{ $user->first_name ?? '' }} {{ $user->last_name ?? '' }}">
                                                    {{ $initials }}
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="font-medium text-gray-900">{{ $user->first_name ?? '' }} {{ $user->last_name ?? '' }}</span>
                                                <span class="block text-xs text-gray-500">#{{ $enrollment->roll_number }}</span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="flex gap-1 status-buttons">
                                                    <button type="button" class="status-btn px-3 py-1 rounded text-sm font-medium border transition present-btn {{ $status === 'present' ? 'bg-green-600 text-white border-green-600' : 'bg-white text-gray-600 border-gray-300 hover:border-green-400' }}" data-status="present">✓ {{ __('Present') }}</button>
                                                    <button type="button" class="status-btn px-3 py-1 rounded text-sm font-medium border transition absent-btn {{ $status === 'absent' ? 'bg-red-600 text-white border-red-600' : 'bg-white text-gray-600 border-gray-300 hover:border-red-400' }}" data-status="absent">✗ {{ __('Absent') }}</button>
                                                    <button type="button" class="status-btn px-3 py-1 rounded text-sm font-medium border transition late-btn {{ $status === 'late' ? 'bg-amber-500 text-white border-amber-500' : 'bg-white text-gray-600 border-gray-300 hover:border-amber-400' }}" data-status="late">~ {{ __('Late') }}</button>
                                                </div>
                                                <input type="hidden" name="attendance[{{ $sid }}][status]" value="{{ $status }}" class="status-input">
                                            </td>
                                            <td class="px-4 py-3">
                                                <input type="text" name="attendance[{{ $sid }}][note]" value="{{ old("attendance.{$sid}.note", $note) }}" placeholder="{{ __('Optional note') }}" class="block w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div id="summary-bar" class="mt-6 p-4 bg-gray-100 rounded-lg text-sm font-medium text-gray-700 sticky bottom-0">
                            <span id="summary-text">✓ <span id="count-present">0</span> {{ __('Present') }} &nbsp; ✗ <span id="count-absent">0</span> {{ __('Absent') }} &nbsp; ~ <span id="count-late">0</span> {{ __('Late') }}</span>
                        </div>

                        <div class="mt-6 flex gap-4">
                            <button type="submit" class="inline-flex justify-center items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500">
                                {{ __('Save Attendance') }}
                            </button>
                            <a href="{{ route('teacher.attendance.index') }}" class="inline-flex items-center px-4 py-2 text-gray-700 hover:text-gray-900">{{ __('Cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function() {
            function updateSummary() {
                var present = 0, absent = 0, late = 0;
                document.querySelectorAll('.status-input').forEach(function(input) {
                    var v = (input.value || '').trim();
                    if (v === 'present') present++;
                    else if (v === 'absent') absent++;
                    else if (v === 'late') late++;
                });
                var elPresent = document.getElementById('count-present');
                var elAbsent = document.getElementById('count-absent');
                var elLate = document.getElementById('count-late');
                if (elPresent) elPresent.textContent = present;
                if (elAbsent) elAbsent.textContent = absent;
                if (elLate) elLate.textContent = late;
            }

            document.querySelectorAll('.attendance-row').forEach(function(row) {
                var statusInput = row.querySelector('.status-input');
                var btns = row.querySelectorAll('.status-btn');
                if (!statusInput || !btns.length) return;

                btns.forEach(function(btn) {
                    btn.addEventListener('click', function() {
                        var status = btn.getAttribute('data-status');
                        statusInput.value = status;
                        btns.forEach(function(b) {
                            var s = b.getAttribute('data-status');
                            b.classList.remove('bg-green-600', 'text-white', 'border-green-600', 'bg-red-600', 'border-red-600', 'bg-amber-500', 'border-amber-500');
                            b.classList.add('bg-white', 'text-gray-600', 'border-gray-300');
                            if (s === status) {
                                b.classList.remove('bg-white', 'text-gray-600', 'border-gray-300');
                                if (s === 'present') { b.classList.add('bg-green-600', 'text-white', 'border-green-600'); }
                                else if (s === 'absent') { b.classList.add('bg-red-600', 'text-white', 'border-red-600'); }
                                else if (s === 'late') { b.classList.add('bg-amber-500', 'text-white', 'border-amber-500'); }
                            }
                        });
                        updateSummary();
                    });
                });
            });

            document.getElementById('mark-all-present').addEventListener('click', function() {
                document.querySelectorAll('.attendance-row').forEach(function(row) {
                    var statusInput = row.querySelector('.status-input');
                    var presentBtn = row.querySelector('.present-btn');
                    if (statusInput && presentBtn) {
                        statusInput.value = 'present';
                        row.querySelectorAll('.status-btn').forEach(function(b) {
                            b.classList.remove('bg-green-600', 'text-white', 'border-green-600', 'bg-red-600', 'border-red-600', 'bg-amber-500', 'border-amber-500');
                            b.classList.add('bg-white', 'text-gray-600', 'border-gray-300');
                        });
                        presentBtn.classList.remove('bg-white', 'text-gray-600', 'border-gray-300');
                        presentBtn.classList.add('bg-green-600', 'text-white', 'border-green-600');
                    }
                });
                updateSummary();
            });

            updateSummary();
        })();
    </script>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Attendance') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            @if (session('success'))
                <div class="rounded-md bg-green-50 p-4 text-green-800">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="rounded-md bg-red-50 p-4 text-red-800">{{ session('error') }}</div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Select Class to Take Attendance') }}</h3>
                    <form action="{{ route('teacher.attendance.mark') }}" method="GET" id="attendance-form">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                            <div>
                                <label for="class_id" class="block text-sm font-medium text-gray-700">{{ __('Class') }}</label>
                                <select name="class_id" id="class_id" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">{{ __('Select class') }}</option>
                                    @foreach ($classes as $c)
                                        <option value="{{ $c->id }}" data-sections='@json($c->sections->map(fn($s) => ['id' => $s->id, 'name' => $s->name]))' {{ request('class_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="section_id" class="block text-sm font-medium text-gray-700">{{ __('Section') }}</label>
                                <select name="section_id" id="section_id" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">{{ __('Select section') }}</option>
                                </select>
                            </div>
                            <div>
                                <label for="date" class="block text-sm font-medium text-gray-700">{{ __('Date') }}</label>
                                <input type="date" name="date" id="date" required
                                    max="{{ date('Y-m-d') }}"
                                    value="{{ request('date', date('Y-m-d')) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <button type="submit" class="w-full md:w-auto inline-flex justify-center items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500">
                                    {{ __('Take Attendance') }}
                                </button>
                            </div>
                        </div>
                    </form>
                    <script>
                        (function() {
                            var classSelect = document.getElementById('class_id');
                            var sectionSelect = document.getElementById('section_id');
                            var selectedSectionId = '{{ request('section_id') }}';
                            function updateSections() {
                                var opt = classSelect.options[classSelect.selectedIndex];
                                sectionSelect.innerHTML = '<option value="">Select section</option>';
                                if (!opt || !opt.value) return;
                                try {
                                    var sections = JSON.parse(opt.getAttribute('data-sections') || '[]');
                                    sections.forEach(function(s) {
                                        var o = document.createElement('option');
                                        o.value = s.id;
                                        o.textContent = s.name;
                                        if (String(s.id) === String(selectedSectionId)) o.selected = true;
                                        sectionSelect.appendChild(o);
                                    });
                                } catch (e) {}
                            }
                            classSelect.addEventListener('change', updateSections);
                            updateSections();
                        })();
                    </script>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Attendance History') }} ({{ __('last 14 days') }})</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Date') }}</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Class') }}</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Section') }}</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Present') }}</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Absent') }}</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Late') }}</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Rate') }}</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Edit') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($history as $h)
                                    <tr>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">{{ $h->date->format('M j, Y') }}</td>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">{{ $h->class_name }}</td>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">{{ $h->section_name }}</td>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm text-right text-green-600">{{ $h->present }}</td>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm text-right text-red-600">{{ $h->absent }}</td>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm text-right text-amber-600">{{ $h->late }}</td>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm text-right">{{ $h->rate }}%</td>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm">
                                            <a href="{{ route('teacher.attendance.mark', ['class_id' => $h->school_class_id, 'section_id' => $h->section_id, 'date' => $h->date->toDateString()]) }}" class="text-indigo-600 hover:text-indigo-900">{{ __('Edit') }}</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-4 py-6 text-center text-sm text-gray-500">{{ __('No attendance records in the last 14 days.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>

@extends('layouts.student-app')

@section('content')
<div class="flex items-end justify-between mb-8">
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-slate-900 tracking-tight">{{ __('My Attendance') }}</h1>
        <p class="text-slate-600 mt-1.5 font-medium text-sm">{{ __('Monthly report and history.') }}</p>
    </div>
</div>

@if (! $enrollment)
    <div class="mb-6 rounded-2xl bg-amber-50 border border-amber-200 p-5 flex items-center gap-4">
        <div class="bg-amber-100 text-amber-600 p-3 rounded-xl">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
        </div>
        <div>
            <p class="font-bold text-amber-900 text-sm">{{ __('Enrollment Required') }}</p>
            <p class="text-amber-800/80 text-xs mt-0.5">{{ __('Contact administration to be assigned to a class and see your attendance.') }}</p>
        </div>
    </div>
@endif

<div class="max-w-4xl space-y-8">
            {{-- Summary: 4 stat cards --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5">
                    <p class="text-sm font-medium text-gray-500">{{ __('Total Days') }}</p>
                    <p class="mt-1 text-2xl font-bold text-gray-900">{{ $total }}</p>
                </div>
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5">
                    <p class="text-sm font-medium text-gray-500">{{ __('Present') }}</p>
                    <p class="mt-1 text-2xl font-bold text-green-600">{{ $present }}</p>
                </div>
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5">
                    <p class="text-sm font-medium text-gray-500">{{ __('Absent') }}</p>
                    <p class="mt-1 text-2xl font-bold text-red-600">{{ $absent }}</p>
                </div>
                @php
                    $rateBorder = $rate >= 90 ? 'border-green-500' : ($rate >= 75 ? 'border-amber-500' : 'border-red-500');
                @endphp
                <div class="bg-white rounded-lg border-2 {{ $rateBorder }} shadow-sm p-5">
                    <p class="text-sm font-medium text-gray-500">{{ __('Rate') }}</p>
                    <p class="mt-1 text-2xl font-bold text-gray-900">{{ $rate }}%</p>
                </div>
            </div>

            {{-- Monthly calendar --}}
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <button type="button" id="cal-prev" class="px-3 py-1.5 rounded border border-gray-300 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">&larr; {{ __('Prev') }}</button>
                    <h3 id="cal-month-label" class="text-lg font-semibold text-gray-900"></h3>
                    <button type="button" id="cal-next" class="px-3 py-1.5 rounded border border-gray-300 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">{{ __('Next') }} &rarr;</button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse" id="cal-grid">
                        <thead>
                            <tr class="text-center text-xs font-medium text-gray-500">
                                <th class="p-2 w-12">{{ __('Mon') }}</th>
                                <th class="p-2 w-12">{{ __('Tue') }}</th>
                                <th class="p-2 w-12">{{ __('Wed') }}</th>
                                <th class="p-2 w-12">{{ __('Thu') }}</th>
                                <th class="p-2 w-12">{{ __('Fri') }}</th>
                                <th class="p-2 w-12">{{ __('Sat') }}</th>
                                <th class="p-2 w-12">{{ __('Sun') }}</th>
                            </tr>
                        </thead>
                        <tbody id="cal-body"></tbody>
                    </table>
                </div>
                <div class="mt-4 flex flex-wrap gap-4 text-sm text-gray-600">
                    <span><span class="inline-block w-3 h-3 rounded-full bg-green-500 align-middle mr-1"></span> {{ __('Present') }}</span>
                    <span><span class="inline-block w-3 h-3 rounded-full bg-red-500 align-middle mr-1"></span> {{ __('Absent') }}</span>
                    <span><span class="inline-block w-3 h-3 rounded-full bg-amber-500 align-middle mr-1"></span> {{ __('Late') }}</span>
                </div>
            </div>

            {{-- Detail table (filtered by month) --}}
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Attendance Details') }} — <span id="table-month-label"></span></h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Date') }}</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Day') }}</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Status') }}</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Note') }}</th>
                            </tr>
                        </thead>
                        <tbody id="detail-tbody" class="bg-white divide-y divide-gray-200">
                            @foreach ($attendances as $a)
                                <tr class="detail-row" data-month="{{ $a->date->format('Y-m') }}">
                                    <td class="px-4 py-2 text-sm text-gray-900">{{ $a->date->format('M j, Y') }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-600">{{ $a->date->format('l') }}</td>
                                    <td class="px-4 py-2">
                                        @if ($a->status === 'present')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">{{ __('Present') }}</span>
                                        @elseif ($a->status === 'absent')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">{{ __('Absent') }}</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">{{ __('Late') }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-500">{{ $a->note ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <p id="detail-empty" class="hidden py-6 text-center text-sm text-gray-500">{{ __('No attendance records for this month') }}</p>
            </div>
</div>

<script>
        (function() {
            var attendanceByDate = @json($attendanceByDate);
            var today = new Date();
            var current = { year: today.getFullYear(), month: today.getMonth() };

            function pad(n) { return n < 10 ? '0' + n : n; }
            function dateKey(y, m, d) { return y + '-' + pad(m + 1) + '-' + pad(d); }

            function monthLabel(y, m) {
                var names = ['January','February','March','April','May','June','July','August','September','October','November','December'];
                return names[m] + ' ' + y;
            }

            function renderCalendar() {
                var y = current.year, m = current.month;
                var first = new Date(y, m, 1);
                var last = new Date(y, m + 1, 0);
                var daysInMonth = last.getDate();
                var startDow = (first.getDay() + 6) % 7;
                var rows = [];
                var row = [];
                for (var i = 0; i < startDow; i++) row.push({ day: '', status: null });
                for (var d = 1; d <= daysInMonth; d++) {
                    var key = dateKey(y, m, d);
                    var status = attendanceByDate[key] || null;
                    var isToday = today.getFullYear() === y && today.getMonth() === m && today.getDate() === d;
                    row.push({ day: d, status: status, isToday: isToday });
                    if (row.length === 7) { rows.push(row); row = []; }
                }
                if (row.length) {
                    while (row.length < 7) row.push({ day: '', status: null });
                    rows.push(row);
                }

                var tbody = document.getElementById('cal-body');
                tbody.innerHTML = rows.map(function(r) {
                    return '<tr>' + r.map(function(cell) {
                        if (cell.day === '') return '<td class="p-1.5 h-10 w-12 border border-gray-100 bg-gray-50/50"></td>';
                        var bg = cell.status === 'present' ? 'bg-green-500 text-white' : cell.status === 'absent' ? 'bg-red-500 text-white' : cell.status === 'late' ? 'bg-amber-500 text-white' : 'bg-white text-gray-700';
                        var border = cell.isToday ? ' ring-2 ring-indigo-500 ring-inset font-bold' : ' border border-gray-200';
                        return '<td class="p-1.5 h-10 w-12 text-center text-sm' + border + ' ' + bg + '">' + cell.day + '</td>';
                    }).join('') + '</tr>';
                }).join('');

                document.getElementById('cal-month-label').textContent = monthLabel(y, m);
                document.getElementById('table-month-label').textContent = monthLabel(y, m);
                filterTable(y, m);
            }

            function filterTable(y, m) {
                var monthKey = y + '-' + pad(m + 1);
                var rows = document.querySelectorAll('.detail-row');
                var visible = 0;
                rows.forEach(function(tr) {
                    var show = tr.getAttribute('data-month') === monthKey;
                    tr.style.display = show ? '' : 'none';
                    if (show) visible++;
                });
                document.getElementById('detail-empty').classList.toggle('hidden', visible > 0);
            }

            document.getElementById('cal-prev').addEventListener('click', function() {
                current.month--;
                if (current.month < 0) { current.month = 11; current.year--; }
                renderCalendar();
            });
            document.getElementById('cal-next').addEventListener('click', function() {
                current.month++;
                if (current.month > 11) { current.month = 0; current.year++; }
                renderCalendar();
            });

            renderCalendar();
        })();
</script>
@endsection

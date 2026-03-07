@extends('layouts.teacher-app')

@section('content')
<div class="flex items-end justify-between mb-8">
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-slate-900 tracking-tight">{{ $subject->name }} — {{ $schoolClass->name }}</h1>
        <p class="text-slate-600 mt-1.5 font-medium text-sm">Gradebook: enter scores and export PDF.</p>
    </div>
</div>

@if (session('success'))
    <div class="mb-6 rounded-xl bg-emerald-50 border border-emerald-200/80 p-4 text-sm font-bold text-emerald-800">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="mb-6 rounded-xl bg-red-50 border border-red-200/80 p-4 text-sm font-bold text-red-800">{{ session('error') }}</div>
@endif

<div id="class-summary-bar" class="mb-6 p-5 bg-indigo-50/80 rounded-xl shadow border border-indigo-200/60 text-sm font-bold text-slate-700">
    {{ __('Class Average') }}: <span id="class-avg">—</span>% |
    {{ __('Highest') }}: <span id="class-high">—</span> |
    {{ __('Lowest') }}: <span id="class-low">—</span> |
    {{ __('Pass Rate') }}: <span id="class-pass">—</span>%
</div>

<div class="mb-6">
    <button type="button" id="add-assessment-btn" class="inline-flex items-center px-5 py-3 border border-slate-200 rounded-xl text-sm font-bold text-slate-700 bg-white hover:bg-slate-50 shadow transition-all">
        {{ __('Add Assessment') }}
    </button>
</div>

<form action="{{ route('teacher.grades.store', [$classId, $subjectId]) }}" method="POST" id="gradebook-form">
    @csrf
    <div id="assessment-names-container"></div>

    <div class="overflow-x-auto rounded-xl border border-slate-200/80 bg-white/95 shadow-lg">
        <table class="min-w-full divide-y divide-slate-200" id="gradebook-table">
            <thead class="bg-slate-100">
                <tr>
                    <th class="sticky left-0 z-10 bg-slate-100 px-5 py-3.5 text-left text-xs font-bold text-slate-600 uppercase tracking-wider w-48">{{ __('Student Name') }}</th>
                    <th class="sticky left-[12rem] z-10 bg-slate-100 px-5 py-3.5 text-left text-xs font-bold text-slate-600 uppercase tracking-wider w-24">{{ __('Roll No') }}</th>
                    @foreach ($assessments as $assessmentName)
                        <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-600 uppercase tracking-wider min-w-[100px] assessment-header" data-assessment="{{ e($assessmentName) }}">{{ $assessmentName }}</th>
                    @endforeach
                    <th class="px-5 py-3.5 text-right text-xs font-bold text-slate-600 uppercase tracking-wider w-20">{{ __('Average') }}</th>
                    <th class="px-5 py-3.5 text-center text-xs font-bold text-slate-600 uppercase tracking-wider w-16">{{ __('Grade') }}</th>
                    <th class="px-5 py-3.5 text-center text-xs font-bold text-slate-600 uppercase tracking-wider w-20">{{ __('Status') }}</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-200">
                @foreach ($students as $enrollment)
                    @php
                        $sid = $enrollment->student_id;
                        $user = $enrollment->user;
                        $studentGrades = $grades->get($sid) ?? collect();
                    @endphp
                    <tr class="grade-row" data-student-id="{{ $sid }}" data-roll="{{ $enrollment->roll_number }}">
                        <td class="sticky left-0 z-10 bg-white px-5 py-2.5 text-sm font-medium text-slate-900 border-r border-slate-100">{{ $user->first_name ?? '' }} {{ $user->last_name ?? '' }}</td>
                        <td class="sticky left-[12rem] z-10 bg-white px-5 py-2.5 text-sm text-slate-500 border-r border-slate-100">{{ $enrollment->roll_number }}</td>
                        @foreach ($assessments as $assessmentName)
                            @php
                                $existingScore = $studentGrades->get($assessmentName)?->score;
                            @endphp
                            <td class="px-5 py-2">
                                <input type="number" min="0" max="100" step="0.5"
                                    name="grades[{{ $sid }}][{{ $assessmentName }}]"
                                    value="{{ $existingScore !== null ? (float) $existingScore : '' }}"
                                    class="grade-input block w-full text-sm rounded-lg border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 py-1.5"
                                    data-student="{{ $sid }}">
                            </td>
                        @endforeach
                        <td class="px-5 py-2.5 text-right text-sm font-medium text-slate-800 average-cell" data-student="{{ $sid }}">—</td>
                        <td class="px-5 py-2.5 text-center text-sm font-bold grade-cell text-slate-800" data-student="{{ $sid }}">—</td>
                        <td class="px-5 py-2.5 text-center text-sm font-medium status-cell" data-student="{{ $sid }}">—</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-8 flex flex-wrap gap-4">
        <button type="submit" class="inline-flex justify-center items-center px-5 py-3 bg-indigo-600 text-white rounded-xl text-sm font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition-all active:scale-[0.98]">
            {{ __('Save All Scores') }}
        </button>
        <a href="{{ route('teacher.grades.pdf', [$classId, $subjectId]) }}" class="inline-flex items-center px-5 py-3 border border-slate-200 rounded-xl text-slate-700 bg-white hover:bg-slate-50 font-bold text-sm shadow transition-all" target="_blank">
            {{ __('Export PDF') }}
        </a>
        <a href="{{ route('teacher.grades.index') }}" class="inline-flex items-center px-4 py-3 text-slate-600 hover:text-slate-900 font-bold text-sm">{{ __('Back to Grades') }}</a>
    </div>
</form>

@push('scripts')
<script>
(function() {
    var table = document.getElementById('gradebook-table');
    var tbody = table && table.querySelector('tbody');
    var assessmentNamesContainer = document.getElementById('assessment-names-container');
    var existingAssessments = @json($assessments->values()->all());

    function addAssessmentColumn(name) {
        if (!name || !tbody) return;
        name = String(name).trim();
        if (!name) return;
        var rows = tbody.querySelectorAll('.grade-row');
        rows.forEach(function(tr) {
            var studentId = tr.getAttribute('data-student-id');
            var td = document.createElement('td');
            td.className = 'px-4 py-1';
            var input = document.createElement('input');
            input.type = 'number';
            input.min = 0;
            input.max = 100;
            input.step = 0.5;
            input.name = 'grades[' + studentId + '][' + name + ']';
            input.className = 'grade-input block w-full text-sm rounded-lg border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500';
            input.setAttribute('data-student', studentId);
            td.appendChild(input);
            var avgCell = tr.querySelector('.average-cell');
            tr.insertBefore(td, avgCell);
        });
        var headerRow = table.querySelector('thead tr');
        var th = document.createElement('th');
        th.className = 'px-5 py-3.5 text-left text-xs font-bold text-slate-600 uppercase tracking-wider min-w-[100px] assessment-header';
        th.setAttribute('data-assessment', name);
        th.textContent = name;
        var ths = headerRow.querySelectorAll('th');
        var insertBeforeEl = ths[ths.length - 3];
        headerRow.insertBefore(th, insertBeforeEl);
        var hidden = document.createElement('input');
        hidden.type = 'hidden';
        hidden.name = 'assessment_names[]';
        hidden.value = name;
        assessmentNamesContainer.appendChild(hidden);
        existingAssessments.push(name);
        updateAllRows();
    }

    function getGradeLetter(avg) {
        if (avg == null) return '—';
        if (avg >= 90) return 'A';
        if (avg >= 80) return 'B';
        if (avg >= 70) return 'C';
        if (avg >= 60) return 'D';
        return 'F';
    }

    function getRowValues(studentId) {
        var row = tbody && tbody.querySelector('.grade-row[data-student-id="' + studentId + '"]');
        if (!row) return [];
        var inputs = row.querySelectorAll('.grade-input');
        var values = [];
        inputs.forEach(function(inp) {
            var v = parseFloat(inp.value);
            if (!isNaN(v) && inp.value.trim() !== '') values.push(v);
        });
        return values;
    }

    function getRowAverage(studentId) {
        var values = getRowValues(studentId);
        if (values.length === 0) return null;
        return values.reduce(function(a, b) { return a + b; }, 0) / values.length;
    }

    function updateRow(studentId) {
        var row = tbody && tbody.querySelector('.grade-row[data-student-id="' + studentId + '"]');
        if (!row) return;
        var avg = getRowAverage(studentId);
        var avgCell = row.querySelector('.average-cell');
        var gradeCell = row.querySelector('.grade-cell');
        var statusCell = row.querySelector('.status-cell');
        if (avgCell) avgCell.textContent = avg !== null ? avg.toFixed(1) : '—';
        if (gradeCell) {
            gradeCell.textContent = getGradeLetter(avg);
            gradeCell.className = 'px-4 py-2 text-center text-sm font-bold grade-cell text-slate-800';
        }
        if (statusCell) {
            if (avg === null) {
                statusCell.textContent = '—';
                statusCell.className = 'px-4 py-2 text-center text-sm status-cell text-slate-500';
            } else if (avg >= 60) {
                statusCell.textContent = 'Pass';
                statusCell.className = 'px-4 py-2 text-center text-sm status-cell text-green-600 font-medium';
            } else {
                statusCell.textContent = 'Fail';
                statusCell.className = 'px-4 py-2 text-center text-sm status-cell text-red-600 font-medium';
            }
        }
        row.classList.remove('border-l-4', 'border-red-400');
        if (avg !== null && avg < 60) row.classList.add('border-l-4', 'border-red-400');
    }

    function updateAllRows() {
        var rows = tbody && tbody.querySelectorAll('.grade-row');
        if (rows) rows.forEach(function(r) { updateRow(r.getAttribute('data-student-id')); });
        updateClassSummary();
    }

    function updateClassSummary() {
        var rows = tbody && tbody.querySelectorAll('.grade-row');
        var averages = [];
        var passCount = 0;
        var total = 0;
        rows.forEach(function(r) {
            var sid = r.getAttribute('data-student-id');
            var avg = getRowAverage(sid);
            if (avg !== null) {
                averages.push(avg);
                total++;
                if (avg >= 60) passCount++;
            }
        });
        var classAvg = averages.length ? (averages.reduce(function(a, b) { return a + b; }, 0) / averages.length).toFixed(1) : '—';
        var high = averages.length ? Math.max.apply(null, averages).toFixed(1) : '—';
        var low = averages.length ? Math.min.apply(null, averages).toFixed(1) : '—';
        var passRate = total ? Math.round((passCount / total) * 100) : '—';
        var elAvg = document.getElementById('class-avg');
        var elHigh = document.getElementById('class-high');
        var elLow = document.getElementById('class-low');
        var elPass = document.getElementById('class-pass');
        if (elAvg) elAvg.textContent = classAvg;
        if (elHigh) elHigh.textContent = high;
        if (elLow) elLow.textContent = low;
        if (elPass) elPass.textContent = passRate;
    }

    tbody && tbody.addEventListener('input', function(e) {
        if (e.target.classList.contains('grade-input')) {
            updateRow(e.target.getAttribute('data-student'));
        }
    });

    document.getElementById('add-assessment-btn').addEventListener('click', function() {
        var name = window.prompt('{{ __("Assessment name (e.g. Quiz 1, Midterm)") }}');
        if (name) addAssessmentColumn(name);
    });

    updateAllRows();
})();
</script>
@endpush
@endsection

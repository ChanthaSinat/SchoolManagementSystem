@php
    $letterColors = ['A' => 'bg-green-600', 'B' => 'bg-green-500', 'C' => 'bg-amber-500', 'D' => 'bg-amber-600', 'F' => 'bg-red-600', '—' => 'bg-gray-400'];
    function barColor($avg) {
        if ($avg === null) return 'bg-gray-300';
        if ($avg >= 80) return 'bg-green-500';
        if ($avg >= 60) return 'bg-amber-500';
        return 'bg-red-500';
    }
    function rowColor($avg) {
        if ($avg === null) return 'text-gray-600';
        if ($avg >= 80) return 'text-green-600';
        if ($avg >= 60) return 'text-amber-600';
        return 'text-red-600';
    }
    function letterGrade($avg) {
        if ($avg === null) return '—';
        if ($avg >= 90) return 'A';
        if ($avg >= 80) return 'B';
        if ($avg >= 70) return 'C';
        if ($avg >= 60) return 'D';
        return 'F';
    }
@endphp
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('My Grades') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-8">
            {{-- Summary row: 3 cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5">
                    <p class="text-sm font-medium text-gray-500">{{ __('Overall Average') }}</p>
                    <p class="mt-1 text-2xl font-bold text-gray-900">
                        {{ $overallAverage !== null ? round($overallAverage, 1) . '%' : '—' }}
                        <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-sm font-medium text-white {{ $letterColors[$overallLetter] ?? 'bg-gray-400' }}">{{ $overallLetter }}</span>
                    </p>
                </div>
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5">
                    <p class="text-sm font-medium text-gray-500">{{ __('Subjects Passing') }}</p>
                    <p class="mt-1 text-2xl font-bold text-green-600">{{ $passingCount }}</p>
                </div>
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5">
                    <p class="text-sm font-medium text-gray-500">{{ __('Subjects Failing') }}</p>
                    <p class="mt-1 text-2xl font-bold {{ $failingCount > 0 ? 'text-red-600' : 'text-gray-900' }}">{{ $failingCount }}</p>
                </div>
            </div>

            {{-- Per-subject cards --}}
            @forelse ($subjectStats as $stat)
                @php
                    $avg = $stat->average;
                    $teacherName = $stat->teacher ? ($stat->teacher->full_name ?? trim(($stat->teacher->first_name ?? '') . ' ' . ($stat->teacher->last_name ?? ''))) : '';
                @endphp
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 flex flex-wrap items-center justify-between gap-2">
                        <span class="font-bold text-gray-900">{{ $stat->subject->name ?? __('Subject') }}</span>
                        <span class="text-sm text-gray-500">{{ $teacherName ? 'Mr/Ms ' . $teacherName : '—' }}</span>
                        <span class="inline-flex items-center px-2.5 py-1 rounded text-sm font-medium text-white {{ $letterColors[$stat->letter] ?? 'bg-gray-400' }}">{{ $stat->letter }}</span>
                    </div>
                    <div class="px-5 py-3">
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="h-2.5 rounded-full {{ barColor($avg) }}" style="width: {{ $avg !== null ? min(100, max(0, $avg)) : 0 }}%"></div>
                        </div>
                    </div>
                    <div class="px-5 pb-4 overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="border-b border-gray-200 text-left text-gray-500">
                                    <th class="py-2 pr-4">{{ __('Assessment') }}</th>
                                    <th class="py-2 pr-4 text-right">{{ __('Score') }}</th>
                                    <th class="py-2 pr-4 text-right">{{ __('Out of') }}</th>
                                    <th class="py-2 pr-4 text-right">{{ __('Percentage') }}</th>
                                    <th class="py-2 text-center">{{ __('Grade') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($stat->grades->sortBy('assessment_name') as $g)
                                    @php
                                        $pct = ($g->max_score > 0 && $g->score !== null) ? ((float)$g->score / (float)$g->max_score) * 100 : null;
                                        $letter = letterGrade($pct);
                                    @endphp
                                    <tr class="border-b border-gray-100">
                                        <td class="py-2 pr-4 text-gray-900">{{ $g->assessment_name }}</td>
                                        <td class="py-2 pr-4 text-right">{{ $g->score !== null ? $g->score : '—' }}</td>
                                        <td class="py-2 pr-4 text-right">{{ $g->max_score }}</td>
                                        <td class="py-2 pr-4 text-right font-medium {{ rowColor($pct) }}">{{ $pct !== null ? round($pct, 1) . '%' : '—' }}</td>
                                        <td class="py-2 text-center">
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium text-white {{ $letterColors[$letter] ?? 'bg-gray-400' }}">{{ $letter }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="px-5 py-3 bg-gray-50 border-t border-gray-100 text-sm font-medium {{ $stat->status === 'pass' ? 'text-green-600' : ($stat->status === 'fail' ? 'text-red-600' : 'text-gray-500') }}">
                        {{ __('Average') }}: {{ $avg !== null ? round($avg, 1) . '%' : '—' }} — {{ $stat->status === 'pass' ? __('Pass') : ($stat->status === 'fail' ? __('Fail') : '—') }}
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-8 text-center text-gray-500">
                    {{ __('No grades recorded yet.') }}
                </div>
            @endforelse

            {{-- Performance Trend --}}
            @if (count($subjectStats) > 0 && count($chartData['labels'] ?? []) > 0)
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Your Performance Trend') }}</h3>
                    <div class="relative h-80">
                        <canvas id="performanceChart"></canvas>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        (function() {
            var chartData = @json($chartData);
            if (!chartData.labels || chartData.labels.length === 0) return;
            var ctx = document.getElementById('performanceChart');
            if (!ctx) return;
            var config = {
                type: 'line',
                data: {
                    labels: chartData.labels,
                    datasets: chartData.datasets.map(function(d) {
                        return {
                            label: d.label,
                            data: d.data,
                            borderColor: d.borderColor,
                            backgroundColor: d.backgroundColor || (d.borderColor + '20'),
                            tension: typeof d.tension !== 'undefined' ? d.tension : 0.4,
                            fill: d.fill !== undefined ? d.fill : false
                        };
                    })
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { min: 0, max: 100 }
                    }
                }
            };
            new Chart(ctx, config);
        })();
    </script>
</x-app-layout>

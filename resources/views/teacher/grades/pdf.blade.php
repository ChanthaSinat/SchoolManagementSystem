<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Grades — {{ $subject->name }} — {{ $schoolClass->name }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 1px solid #333; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 18px; }
        .meta { margin-bottom: 16px; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 6px 8px; text-align: left; }
        th { background: #f5f5f5; font-weight: bold; }
        td.num, th.num { text-align: right; }
        .footer { margin-top: 24px; padding-top: 10px; border-top: 1px solid #333; font-size: 10px; color: #555; }
    </style>
</head>
<body>
    <div class="header">
        <h1>EduCore</h1>
        @if($academicYear ?? null)
            <p style="margin: 4px 0 0 0;">{{ $academicYear->name }}</p>
        @endif
    </div>

    <div class="meta">
        <strong>{{ __('Class') }}:</strong> {{ $schoolClass->name }} &nbsp;|&nbsp;
        <strong>{{ __('Section') }}:</strong> {{ $schoolClass->sections->isEmpty() ? __('All') : $schoolClass->sections->pluck('name')->join(', ') }} &nbsp;|&nbsp;
        <strong>{{ __('Subject') }}:</strong> {{ $subject->name }} &nbsp;|&nbsp;
        <strong>{{ __('Date generated') }}:</strong> {{ now()->format('M j, Y') }}
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30px;">{{ __('Rank') }}</th>
                <th>{{ __('Student Name') }}</th>
                <th class="num" style="width: 60px;">{{ __('Roll No') }}</th>
                @foreach ($assessments as $a)
                    <th class="num" style="width: 50px;">{{ $a }}</th>
                @endforeach
                <th class="num" style="width: 55px;">{{ __('Average') }}</th>
                <th style="width: 45px; text-align: center;">{{ __('Grade') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rows as $row)
                <tr>
                    <td>{{ $row->rank }}</td>
                    <td>{{ $row->enrollment->user->first_name ?? '' }} {{ $row->enrollment->user->last_name ?? '' }}</td>
                    <td class="num">{{ $row->enrollment->roll_number }}</td>
                    @foreach ($assessments as $a)
                        @php $score = $row->scores->get($a)?->score; @endphp
                        <td class="num">{{ $score !== null ? number_format((float) $score, 1) : '—' }}</td>
                    @endforeach
                    <td class="num">{{ $row->average !== null ? number_format($row->average, 1) : '—' }}</td>
                    <td style="text-align: center;">{{ $row->grade_letter }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        {{ __('Class Average') }}: {{ $classAverage !== null ? $classAverage . '%' : '—' }} &nbsp;|&nbsp;
        {{ __('Pass Rate') }}: {{ $passRate }}% &nbsp;|&nbsp;
        {{ __('Teacher') }}: {{ $teacherName }}
    </div>
</body>
</html>

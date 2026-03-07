<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Timetable;
use Illuminate\View\View;

class ScheduleController extends Controller
{
    public function index(): View
    {
        $teacherId = auth()->id();
        $timetable = Timetable::where('user_id', $teacherId)
            ->with(['subject', 'schoolClass', 'section'])
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get()
            ->groupBy('day_of_week');

        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
        $currentDay = strtolower(now()->format('l'));
        if (! in_array($currentDay, $days, true)) {
            $currentDay = 'monday';
        }

        return view('teacher.schedule.index', [
            'timetable' => $timetable,
            'days' => $days,
            'currentDay' => $currentDay,
        ]);
    }
}

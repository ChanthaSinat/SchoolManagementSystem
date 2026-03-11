<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\ClassSchedule;
use Illuminate\View\View;

class ScheduleController extends Controller
{
    public function index(): View
    {
        $teacherId = auth()->id();
        $schedule = ClassSchedule::where('teacher_id', $teacherId)
            ->with(['subject', 'schoolClass'])
            ->orderBy('weekday')
            ->orderBy('period')
            ->get()
            ->groupBy('weekday');

        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
        $currentDay = strtolower(now()->format('l'));
        if (! in_array($currentDay, $days, true)) {
            $currentDay = 'monday';
        }

        return view('teacher.schedule.index', compact('schedule', 'days', 'currentDay'));
    }
}

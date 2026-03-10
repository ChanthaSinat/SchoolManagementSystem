<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\Timetable;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScheduleController extends Controller
{
    public function index(Request $request): View
    {
        $classes = SchoolClass::with('academicYear')
            ->orderBy('academic_year_id')
            ->orderBy('name')
            ->get();

        $selectedClassId = (int) $request->query('class_id', $classes->first()?->id ?? 0);

        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
        $currentDay = strtolower(now()->format('l'));
        if (! in_array($currentDay, $days, true)) {
            $currentDay = 'monday';
        }

        $timetable = collect();
        $selectedClass = null;

        if ($selectedClassId) {
            $selectedClass = $classes->firstWhere('id', $selectedClassId);

            $timetable = Timetable::where('school_class_id', $selectedClassId)
                ->with(['subject', 'section', 'user'])
                ->orderBy('day_of_week')
                ->orderBy('start_time')
                ->get()
                ->groupBy('day_of_week');
        }

        return view('admin.schedule.index', [
            'classes' => $classes,
            'selectedClass' => $selectedClass,
            'selectedClassId' => $selectedClassId,
            'timetable' => $timetable,
            'days' => $days,
            'currentDay' => $currentDay,
        ]);
    }
}


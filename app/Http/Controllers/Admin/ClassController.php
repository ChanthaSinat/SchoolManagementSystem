<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreClassRequest;
use App\Http\Requests\Admin\UpdateClassAssignmentsRequest;
use App\Http\Requests\Admin\UpdateClassRequest;
use App\Services\ClassManagementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\AcademicYear;
use App\Models\ClassSchedule;
use App\Models\SchoolClass;
use App\Models\SchoolType;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\User;

class ClassController extends Controller
{
    public function __construct(
        protected ClassManagementService $classManagementService
    ) {}

    public function index(): View
    {
        $classes = SchoolClass::with(['schoolType', 'academicYear', 'semester'])
            ->orderByDesc('academic_year_id')
            ->orderBy('year_level')
            ->orderBy('section')
            ->paginate(15);

        return view('admin.classes.index', compact('classes'));
    }

    public function create(): View
    {
        return view('admin.classes.create');
    }

    public function store(StoreClassRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Ensure required foreign keys exist, even though they are hidden from the UI
        $schoolType = SchoolType::first() ?? SchoolType::create([
            'name' => 'Default School Type',
            'code' => 'HS',
            'year_levels' => ['12'],
            'is_default' => true,
        ]);

        $activeYear = AcademicYear::first() ?? AcademicYear::create([
            'name' => now()->format('Y') . '-' . now()->addYear()->format('Y'),
            'start_date' => now()->startOfYear(),
            'end_date' => now()->endOfYear(),
            'is_active' => true,
        ]);

        $data['school_type_id'] = $schoolType->id;
        $data['academic_year_id'] = $activeYear->id;

        SchoolClass::create($data);

        return redirect()->route('admin.classes.index')->with('success', __('Class created successfully.'));
    }

    public function edit(SchoolClass $class): View
    {
        $subjects = Subject::orderBy('name')->get();
        $teachers = User::where(function ($q) {
                $q->where('role', 'teacher')->orWhereHas('roles', fn ($r) => $r->where('name', 'teacher'));
            })
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        $assigned = $class->subjects()->withPivot('teacher_id')->get();
        $schedule = $class->schedules->keyBy('weekday');

        return view('admin.classes.edit', compact(
            'class',
            'subjects',
            'teachers',
            'assigned',
            'schedule',
        ));
    }

    public function update(UpdateClassRequest $request, SchoolClass $class): RedirectResponse
    {
        $class->update($request->validated());

        return redirect()->route('admin.classes.index')->with('success', __('Class updated successfully.'));
    }

    public function destroy(SchoolClass $class): RedirectResponse
    {
        $class->delete();

        return redirect()->route('admin.classes.index')->with('success', __('Class deleted successfully.'));
    }

    /**
     * Update subject assignments (subject + teacher) and enrolled students for a class.
     */
    public function updateAssignments(UpdateClassAssignmentsRequest $request, SchoolClass $class): RedirectResponse
    {
        $this->classManagementService->updateAssignments($class, $request->validated());

        return redirect()->route('admin.classes.edit', $class)->with('success', __('Class assignments updated successfully.'));
    }

    /**
     * Store manual schedule.
     */
    public function storeSchedule(Request $request, SchoolClass $class): RedirectResponse
    {
        $data = $request->validate([
            'schedule' => ['required', 'array'],
            'schedule.*.subject_id' => ['nullable', 'exists:subjects,id'],
            'schedule.*.teacher_id' => ['nullable', 'exists:users,id'],
            'schedule.*.room' => ['nullable', 'string', 'max:50'],
            'schedule.*.start_time' => ['nullable', 'date_format:H:i'],
            'schedule.*.end_time' => ['nullable', 'date_format:H:i', 'after:schedule.*.start_time'],
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($class, $data) {
            $class->schedules()->delete();

            foreach ($data['schedule'] as $weekday => $row) {
                $subjectId = $row['subject_id'] ?? null;
                $teacherId = $row['teacher_id'] ?? null;

                // Skip days with no subject/teacher selected
                if (! $subjectId || ! $teacherId) {
                    continue;
                }

                $class->schedules()->create([
                    'subject_id' => $subjectId,
                    'teacher_id' => $teacherId,
                    'weekday' => $weekday,
                    'period' => 1,
                    'room' => $row['room'] ?? null,
                    'start_time' => $row['start_time'] ?? null,
                    'end_time' => $row['end_time'] ?? null,
                ]);
            }
        });

        return redirect()->back()->with('success', __('Schedule updated successfully.'));
    }

    /**
     * Generate random schedule for the class.
     */
    public function generateRandomSchedule(SchoolClass $class): RedirectResponse
    {
        $subjectNames = ['Math', 'Physics', 'Chemistry', 'Social', 'Khmer'];
        $subjects = Subject::whereIn('name', $subjectNames)->get();

        // Auto-create any missing core subjects
        if ($subjects->count() < count($subjectNames)) {
            $existing = $subjects->pluck('name')->all();
            $missing = array_diff($subjectNames, $existing);

            foreach ($missing as $name) {
                Subject::create([
                    'name' => $name,
                    'code' => strtoupper(substr($name, 0, 3)),
                    'school_type_id' => null,
                    'year_level' => '12',
                    'semester_id' => null,
                ]);
            }

            $subjects = Subject::whereIn('name', $subjectNames)->get();
        }

        $teachers = User::where(function ($q) {
                $q->where('role', 'teacher')->orWhereHas('roles', fn ($r) => $r->where('name', 'teacher'));
            })
            ->inRandomOrder()
            ->get();

        $weekdays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];

        if ($teachers->isEmpty()) {
            return redirect()->back()->with('error', __('No teachers found to assign.'));
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($class, $subjects, $teachers, $weekdays) {
            $class->schedules()->delete();

            $shuffledSubjects = $subjects->shuffle();
            
            foreach ($weekdays as $index => $weekday) {
                $subject = $shuffledSubjects[$index];
                $teacher = $teachers->random();

                $class->schedules()->create([
                    'subject_id' => $subject->id,
                    'teacher_id' => $teacher->id,
                    'weekday' => $weekday,
                    'period' => 1,
                    'room' => 'Room ' . rand(101, 505),
                    'start_time' => '08:00',
                    'end_time' => '11:00',
                ]);
            }
        });

        return redirect()->back()->with('success', __('Random schedule generated successfully.'));
    }

    /**
     * Reset schedule for the class.
     */
    public function resetSchedule(SchoolClass $class): RedirectResponse
    {
        $class->schedules()->delete();

        return redirect()->back()->with('success', __('Schedule reset successfully.'));
    }
}


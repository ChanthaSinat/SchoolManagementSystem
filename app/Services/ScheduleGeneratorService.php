<?php

namespace App\Services;

use App\Models\SchoolClass;
use App\Models\Timetable;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ScheduleGeneratorService
{
    /**
     * Days of the week for the schedule.
     */
    protected array $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

    /**
     * Standard time slots for classes.
     */
    protected array $timeSlots = [
        ['start' => '08:00', 'end' => '09:30'],
        ['start' => '10:00', 'end' => '11:30'],
        ['start' => '13:00', 'end' => '14:30'],
        ['start' => '15:00', 'end' => '16:30'],
    ];

    /**
     * Automatically generate a random schedule for a class based on its assigned subjects and teachers.
     *
     * @param SchoolClass $schoolClass
     * @return void
     */
    public function generateForClass(SchoolClass $schoolClass): void
    {
        DB::transaction(function () use ($schoolClass) {
            // Clear existing timetable for this class
            Timetable::where('school_class_id', $schoolClass->id)->delete();

            $subjects = $schoolClass->subjects()->withPivot('teacher_id')->get();
            $sections = $schoolClass->sections;

            if ($subjects->isEmpty()) {
                return;
            }

            foreach ($sections as $section) {
                $availableSlots = $this->getAvailableSlots();
                
                // Shuffle subjects to randomize distribution
                $shuffledSubjects = $subjects->shuffle();

                foreach ($shuffledSubjects as $subject) {
                    if (empty($availableSlots)) {
                        break; // No more slots available for this section
                    }

                    // Pick a random slot
                    $slotIndex = array_rand($availableSlots);
                    $slot = $availableSlots[$slotIndex];
                    unset($availableSlots[$slotIndex]);
                    $availableSlots = array_values($availableSlots);

                    Timetable::create([
                        'school_class_id' => $schoolClass->id,
                        'section_id' => $section->id,
                        'subject_id' => $subject->id,
                        'user_id' => $subject->pivot->teacher_id,
                        'day_of_week' => $slot['day'],
                        'start_time' => $slot['start'],
                        'end_time' => $slot['end'],
                        'room' => 'Room ' . rand(101, 505),
                    ]);
                }
            }
        });
    }

    /**
     * Get all possible data/time slots.
     */
    protected function getAvailableSlots(): array
    {
        $slots = [];
        foreach ($this->days as $day) {
            foreach ($this->timeSlots as $time) {
                $slots[] = [
                    'day' => $day,
                    'start' => $time['start'],
                    'end' => $time['end'],
                ];
            }
        }
        return $slots;
    }
}

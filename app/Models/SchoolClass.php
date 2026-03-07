<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SchoolClass extends Model
{
    use HasFactory;

    protected $table = 'school_classes';

    protected $fillable = [
        'academic_year_id',
        'name',
    ];

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function sections(): BelongsToMany
    {
        return $this->belongsToMany(Section::class, 'class_section', 'class_id', 'section_id')
            ->withTimestamps();
    }

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'class_subject', 'class_id', 'subject_id')
            ->withTimestamps();
    }

    public function teacherClasses(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TeacherClass::class, 'school_class_id');
    }
}

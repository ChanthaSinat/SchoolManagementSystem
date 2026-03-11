<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SchoolType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'year_levels',
        'is_default',
    ];

    protected function casts(): array
    {
        return [
            'year_levels' => 'array',
            'is_default' => 'boolean',
        ];
    }

    public function classes(): HasMany
    {
        return $this->hasMany(SchoolClass::class);
    }

    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class);
    }
}


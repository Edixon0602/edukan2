<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id',
    'course_id',
    'course_title',
    'module_name',
    'grade',
    'requires_revision',
    'attempt_date',
])]
class StudentGrade extends Model
{
    protected function casts(): array
    {
        return [
            'attempt_date' => 'date',
            'requires_revision' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}

<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseEquivalency extends Model
{
    use SoftDeletes, BelongsToTenant;

    protected $table = 'course_equivalencies';
    protected $guarded = [];
    protected $casts = ['metadata' => 'array', 'approved_at' => 'datetime'];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function originalCourse(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'original_course_id');
    }

    public function equivalentCourse(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'equivalent_course_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}

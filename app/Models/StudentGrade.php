<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentGrade extends Model
{
    use SoftDeletes, BelongsToTenant;

    protected $table = 'student_grades';
    protected $guarded = [];
    protected $casts = ['score' => 'decimal:2', 'metadata' => 'array'];

    public function student(): BelongsTo { return $this->belongsTo(Student::class); }
    public function courseClass(): BelongsTo { return $this->belongsTo(CourseClass::class); }
    public function assessment(): BelongsTo { return $this->belongsTo(Assessment::class); }
    public function gradedBy(): BelongsTo { return $this->belongsTo(User::class, 'graded_by'); }
}

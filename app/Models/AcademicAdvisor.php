<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcademicAdvisor extends Model
{
    use SoftDeletes, BelongsToTenant;

    protected $table = 'academic_advisors';

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
        'assigned_date' => 'date',
        'metadata' => 'array',
    ];

    public function lecturer(): BelongsTo
    {
        return $this->belongsTo(Lecturer::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    public function isActive(): bool
    {
        return (bool) $this->is_active;
    }
}

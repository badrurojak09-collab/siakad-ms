<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class GradeSubmission extends Model
{
    use SoftDeletes, BelongsToTenant;

    protected $guarded = [];
    protected $casts = ['submitted_at' => 'datetime', 'published_at' => 'datetime', 'metadata' => 'array'];

    public function courseClass(): BelongsTo { return $this->belongsTo(CourseClass::class); }
    public function submittedBy(): BelongsTo { return $this->belongsTo(User::class, 'submitted_by'); }
    public function approvedBy(): BelongsTo { return $this->belongsTo(User::class, 'approved_by'); }
}

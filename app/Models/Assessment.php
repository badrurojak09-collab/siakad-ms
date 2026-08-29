<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assessment extends Model
{
    use SoftDeletes, BelongsToTenant;

    protected $guarded = [];
    protected $casts = ['weight' => 'decimal:2', 'max_score' => 'decimal:2', 'metadata' => 'array'];

    public function courseClass(): BelongsTo { return $this->belongsTo(CourseClass::class); }
    public function grades(): HasMany { return $this->hasMany(StudentGrade::class, 'assessment_id'); }
}

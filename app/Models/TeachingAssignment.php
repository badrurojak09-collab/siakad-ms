<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeachingAssignment extends Model
{
    use SoftDeletes, BelongsToTenant;

    protected $table = 'teaching_assignments';
    protected $guarded = [];
    protected $casts = ['teaching_load' => 'decimal:2'];

    public function courseClass(): BelongsTo { return $this->belongsTo(CourseClass::class); }
    public function lecturer(): BelongsTo { return $this->belongsTo(Lecturer::class); }
}

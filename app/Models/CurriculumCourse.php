<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CurriculumCourse extends Model
{
    use SoftDeletes, BelongsToTenant;

    protected $table = 'curriculum_courses';
    protected $guarded = [];
    protected $casts = ['metadata' => 'array', 'semester' => 'integer', 'is_mandatory' => 'boolean'];

    public function curriculum(): BelongsTo { return $this->belongsTo(Curriculum::class); }
    public function course(): BelongsTo { return $this->belongsTo(Course::class); }
}

<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Curriculum extends Model
{
    use SoftDeletes, BelongsToTenant;

    protected $table = 'curriculums';
    protected $guarded = [];
    protected $casts = ['metadata' => 'array', 'year' => 'integer'];

    public function studyProgram(): BelongsTo
    {
        return $this->belongsTo(StudyProgram::class);
    }

    public function courses(): HasMany
    {
        return $this->hasMany(CurriculumCourse::class);
    }

    public function templates(): HasMany
    {
        return $this->hasMany(CurriculumTemplate::class);
    }
}

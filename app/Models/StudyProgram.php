<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudyProgram extends Model
{
    use SoftDeletes, BelongsToTenant;

    protected $table = 'study_programs';
    protected $guarded = [];
    protected $casts = ['metadata' => 'array'];

    public function department(): BelongsTo { return $this->belongsTo(Department::class); }
    public function headOfProgram(): BelongsTo { return $this->belongsTo(Lecturer::class, 'head_of_program_id'); }
    public function curriculums(): HasMany { return $this->hasMany(Curriculum::class); }
    public function students(): HasMany { return $this->hasMany(Student::class); }
    public function lecturers(): HasMany { return $this->hasMany(Lecturer::class); }
}

<?php
namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Semester extends Model
{
    use SoftDeletes, BelongsToTenant;

    protected $table = 'semesters';
    protected $guarded = [];
    protected $casts = ['start_date' => 'date', 'end_date' => 'date', 'krs_start_date' => 'date', 'krs_end_date' => 'date', 'exam_start_date' => 'date', 'exam_end_date' => 'date', 'is_active' => 'boolean', 'metadata' => 'array'];

    public function academic()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }

    public function krsHeaders()
    {
        return $this->hasMany(KrsHeader::class);
    }
}

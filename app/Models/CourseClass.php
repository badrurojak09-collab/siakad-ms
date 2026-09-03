<?php
namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseClass extends Model
{
    use SoftDeletes, BelongsToTenant;

    protected $guarded = [];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lecturer()
    {
        return $this->belongsTo(Lecturer::class, 'lecturer_id');
    }

    public function coLecturer()
    {
        return $this->belongsTo(Lecturer::class, 'co_lecturer_id');
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function krsDetails()
    {
        return $this->hasMany(KrsDetail::class);
    }

    public function attendanceSessions()
    {
        return $this->hasMany(AttendanceSession::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function teachingAssignments()
    {
        return $this->hasMany(TeachingAssignment::class);
    }

    public function grades()
    {
        return $this->hasMany(StudentGrade::class);
    }
}

<?php
namespace App\Models;
use App\Models\Concerns\BelongsToTenant; use Illuminate\Database\Eloquent\Model;
class AcademicTranscriptItem extends Model { use BelongsToTenant; protected $guarded=[]; protected $casts=['score'=>'decimal:2','grade_point'=>'decimal:2','quality_points'=>'decimal:2']; public function transcript(){return $this->belongsTo(AcademicTranscript::class,'transcript_id');} public function grade(){return $this->belongsTo(StudentGrade::class,'student_grade_id');} public function course(){return $this->belongsTo(Course::class);} }

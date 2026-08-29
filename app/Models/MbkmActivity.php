<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Concerns\BelongsToTenant;
class MbkmActivity extends Model { use SoftDeletes, BelongsToTenant; protected $table='mbkm_activities'; protected $guarded=[]; protected $casts=['metadata'=>'array','start_date'=>'date','end_date'=>'date']; public function student(){return $this->belongsTo(Student::class);} public function recognitionCourse(){return $this->belongsTo(Course::class,'recognition_course_id');} public function supervisor(){return $this->belongsTo(Lecturer::class,'supervisor_id');} }

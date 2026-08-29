<?php
namespace App\Models;
use App\Models\Concerns\BelongsToTenant; use Illuminate\Database\Eloquent\Model; use Illuminate\Database\Eloquent\SoftDeletes;
class CoursePrerequisite extends Model { use SoftDeletes,BelongsToTenant; protected $guarded=[]; public function course(){return $this->belongsTo(Course::class);} public function prerequisiteCourse(){return $this->belongsTo(Course::class,'prerequisite_course_id');} }

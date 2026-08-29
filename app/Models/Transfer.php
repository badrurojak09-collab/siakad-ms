<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Concerns\BelongsToTenant;
class Transfer extends Model { use SoftDeletes, BelongsToTenant; protected $table='transfers'; protected $guarded=[]; protected $casts=['metadata'=>'array','request_date'=>'date','approved_at'=>'datetime']; public function student(){return $this->belongsTo(Student::class);} public function fromStudyProgram(){return $this->belongsTo(StudyProgram::class,'from_study_program_id');} public function toStudyProgram(){return $this->belongsTo(StudyProgram::class,'to_study_program_id');} public function approver(){return $this->belongsTo(User::class,'approved_by');} }

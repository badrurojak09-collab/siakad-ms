<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Concerns\BelongsToTenant;
class LeaveRequest extends Model { use SoftDeletes, BelongsToTenant; protected $table='leave_requests'; protected $guarded=[]; protected $casts=['metadata'=>'array','approved_at'=>'datetime']; public function student(){return $this->belongsTo(Student::class);} public function semester(){return $this->belongsTo(Semester::class);} public function startSemester(){return $this->belongsTo(Semester::class,'start_semester_id');} public function endSemester(){return $this->belongsTo(Semester::class,'end_semester_id');} public function approver(){return $this->belongsTo(User::class,'approved_by');} }

<?php
namespace App\Models;
use App\Models\Concerns\BelongsToTenant; use Illuminate\Database\Eloquent\Model; use Illuminate\Database\Eloquent\SoftDeletes;
class AttendanceRecord extends Model { use SoftDeletes, BelongsToTenant; protected $guarded=[]; protected $casts=['check_in_at'=>'datetime']; public function session(){return $this->belongsTo(AttendanceSession::class,'attendance_session_id');} public function student(){return $this->belongsTo(Student::class);} }

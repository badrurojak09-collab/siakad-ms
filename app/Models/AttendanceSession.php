<?php
namespace App\Models;
use App\Models\Concerns\BelongsToTenant; use Illuminate\Database\Eloquent\Model; use Illuminate\Database\Eloquent\SoftDeletes;
class AttendanceSession extends Model { use SoftDeletes, BelongsToTenant; protected $guarded=[]; protected $casts=['opened_at'=>'datetime','closed_at'=>'datetime','meeting_date'=>'date']; public function courseClass(){return $this->belongsTo(CourseClass::class);} public function records(){return $this->hasMany(AttendanceRecord::class);} }

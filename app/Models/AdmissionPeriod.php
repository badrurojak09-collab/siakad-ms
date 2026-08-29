<?php
namespace App\Models;
use App\Models\Concerns\BelongsToTenant; use Illuminate\Database\Eloquent\Model; use Illuminate\Database\Eloquent\SoftDeletes;
class AdmissionPeriod extends Model { use SoftDeletes,BelongsToTenant; protected $table='admission_periods'; protected $guarded=[]; protected $casts=['registration_start'=>'date','registration_end'=>'date','selection_end'=>'date','requirements'=>'array']; public function applicants(){return $this->hasMany(Applicant::class,'admission_period_id');} }

<?php
namespace App\Models;
use App\Models\Concerns\BelongsToTenant; use Illuminate\Database\Eloquent\Model; use Illuminate\Database\Eloquent\SoftDeletes;
class Applicant extends Model { use SoftDeletes,BelongsToTenant; protected $table='applicants'; protected $guarded=[]; protected $casts=['selection_score'=>'decimal:2','submitted_at'=>'datetime','converted_at'=>'datetime']; public function period(){return $this->belongsTo(AdmissionPeriod::class,'admission_period_id');} public function selections(){return $this->hasMany(AdmissionSelection::class);} public function documents(){return $this->hasMany(AdmissionDocument::class);} public function bills(){return $this->hasMany(AdmissionBill::class);} public function student(){return $this->belongsTo(Student::class);} }

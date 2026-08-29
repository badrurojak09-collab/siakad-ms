<?php
namespace App\Models;
use App\Models\Concerns\BelongsToTenant; use Illuminate\Database\Eloquent\Model; use Illuminate\Database\Eloquent\SoftDeletes;
class GraduationCeremony extends Model { use SoftDeletes,BelongsToTenant; protected $guarded=[]; protected $casts=['ceremony_date'=>'date','is_active'=>'boolean']; public function registrations(){return $this->hasMany(CeremonyRegistration::class,'ceremony_id');} }

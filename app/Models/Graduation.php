<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Concerns\BelongsToTenant;
class Graduation extends Model { use SoftDeletes, BelongsToTenant; protected $table='graduations'; protected $guarded=[]; protected $casts=['metadata'=>'array','graduation_date'=>'date','decree_date'=>'date','approved_at'=>'datetime','gpa_final'=>'decimal:2']; public function student(){return $this->belongsTo(Student::class);} public function semester(){return $this->belongsTo(Semester::class);} public function documents(){return $this->hasMany(GraduationDocument::class);} }

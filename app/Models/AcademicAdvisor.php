<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Concerns\BelongsToTenant;
class AcademicAdvisor extends Model { use SoftDeletes, BelongsToTenant; protected $table='academic_advisors'; protected $guarded=[]; protected $casts=['metadata'=>'array']; }

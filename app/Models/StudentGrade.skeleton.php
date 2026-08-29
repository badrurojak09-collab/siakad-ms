<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Concerns\BelongsToTenant;
class StudentGrade extends Model { use SoftDeletes, BelongsToTenant; protected $table='student_grades'; protected $guarded=[]; protected $casts=['metadata'=>'array']; }

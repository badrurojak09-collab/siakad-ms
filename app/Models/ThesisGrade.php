<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Concerns\BelongsToTenant;
class ThesisGrade extends Model { use SoftDeletes, BelongsToTenant; protected $table='thesis_grades'; protected $guarded=[]; protected $casts=['metadata'=>'array']; }

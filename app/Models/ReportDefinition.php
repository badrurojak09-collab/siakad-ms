<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Concerns\BelongsToTenant;
class ReportDefinition extends Model { use SoftDeletes, BelongsToTenant; protected $table='report_definitions'; protected $guarded=[]; protected $casts=['metadata'=>'array']; }

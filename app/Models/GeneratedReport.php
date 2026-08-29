<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model; use Illuminate\Database\Eloquent\SoftDeletes; use App\Models\Concerns\BelongsToTenant;
class GeneratedReport extends Model { use SoftDeletes,BelongsToTenant; protected $table='generated_reports'; protected $guarded=[]; protected $casts=['parameters_used'=>'array','metadata'=>'array','generated_at'=>'datetime','expiry_date'=>'datetime']; public function definition(){return $this->belongsTo(ReportDefinition::class,'report_definition_id');} }

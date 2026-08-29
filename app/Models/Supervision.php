<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Concerns\BelongsToTenant;
class Supervision extends Model { use SoftDeletes, BelongsToTenant; protected $table='supervisions'; protected $guarded=[]; protected $casts=['metadata'=>'array','meeting_date'=>'datetime']; public function thesis(){return $this->belongsTo(Thesis::class);} public function supervisor(){return $this->belongsTo(Lecturer::class,'supervisor_id');} }

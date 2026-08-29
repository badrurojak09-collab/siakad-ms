<?php
namespace App\Models;
use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class KrsDetail extends Model { use SoftDeletes, BelongsToTenant; protected $guarded=[]; protected $casts=['registered_at'=>'datetime']; public function krsHeader(){return $this->belongsTo(KrsHeader::class);} public function courseClass(){return $this->belongsTo(CourseClass::class);} }

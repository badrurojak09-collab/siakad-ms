<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Concerns\BelongsToTenant;
class CeremonyRegistration extends Model { use SoftDeletes, BelongsToTenant; protected $table='ceremony_registrations'; protected $guarded=[]; protected $casts=['metadata'=>'array']; }

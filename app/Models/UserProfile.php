<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Concerns\BelongsToTenant;
class UserProfile extends Model { use SoftDeletes, BelongsToTenant; protected $table='user_profiles'; protected $guarded=[]; protected $casts=['metadata'=>'array']; }

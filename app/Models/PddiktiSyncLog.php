<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Concerns\BelongsToTenant;
class PddiktiSyncLog extends Model { use SoftDeletes, BelongsToTenant; protected $table='pddikti_sync_logs'; protected $guarded=[]; protected $casts=['payload'=>'array','metadata'=>'array','last_attempt_at'=>'datetime','synced_at'=>'datetime']; }

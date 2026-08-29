<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use SoftDeletes, BelongsToTenant;

    protected $table = 'rooms';
    protected $guarded = [];
    protected $casts = ['metadata' => 'array', 'capacity' => 'integer', 'floor' => 'integer', 'is_active' => 'boolean'];

    public function schedules(): HasMany { return $this->hasMany(Schedule::class); }
}

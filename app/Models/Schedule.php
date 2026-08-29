<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schedule extends Model
{
    use SoftDeletes, BelongsToTenant;

    protected $guarded = [];
    protected $casts = ['day_of_week' => 'integer', 'week_number' => 'integer', 'is_online' => 'boolean'];

    public function courseClass(): BelongsTo { return $this->belongsTo(CourseClass::class); }
    public function room(): BelongsTo { return $this->belongsTo(Room::class); }
    public function lecturer(): BelongsTo { return $this->belongsTo(Lecturer::class); }
}

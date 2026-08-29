<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttendanceCorrection extends Model
{
    use SoftDeletes, BelongsToTenant;

    protected $table = 'attendance_corrections';
    protected $guarded = [];
    protected $casts = ['metadata' => 'array'];

    public function attendanceRecord(): BelongsTo { return $this->belongsTo(AttendanceRecord::class); }
    public function requestedBy(): BelongsTo { return $this->belongsTo(User::class, 'requested_by'); }
    public function approvedBy(): BelongsTo { return $this->belongsTo(User::class, 'approved_by'); }
}

<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class KrsHeader extends Model
{
    use SoftDeletes, BelongsToTenant;

    protected $guarded = [];
    protected $casts = ['total_credits' => 'integer', 'submitted_at' => 'datetime', 'advisor_approved_at' => 'datetime', 'metadata' => 'array'];

    public function student(): BelongsTo { return $this->belongsTo(Student::class); }
    public function semester(): BelongsTo { return $this->belongsTo(Semester::class); }
    public function approvedBy(): BelongsTo { return $this->belongsTo(User::class, 'approved_by'); }
    public function details(): HasMany { return $this->hasMany(KrsDetail::class); }
    public function logs(): HasMany { return $this->hasMany(KrsLog::class); }
}

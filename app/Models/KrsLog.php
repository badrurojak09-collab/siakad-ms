<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class KrsLog extends Model
{
    use SoftDeletes, BelongsToTenant;

    protected $guarded = [];
    protected $casts = ['changed_at' => 'datetime', 'metadata' => 'array'];

    public function krsHeader(): BelongsTo { return $this->belongsTo(KrsHeader::class); }
    public function changedBy(): BelongsTo { return $this->belongsTo(User::class, 'changed_by'); }
}

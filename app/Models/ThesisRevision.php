<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ThesisRevision extends Model
{
    use SoftDeletes, BelongsToTenant;
    protected $guarded = [];
    protected $casts = ['submitted_at' => 'datetime', 'approved_at' => 'datetime'];
    public function thesis() { return $this->belongsTo(Thesis::class); }
}

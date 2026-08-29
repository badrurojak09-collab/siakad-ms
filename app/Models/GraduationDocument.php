<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GraduationDocument extends Model
{
    use SoftDeletes, BelongsToTenant;
    protected $guarded = [];
    protected $casts = ['metadata' => 'array', 'generated_at' => 'datetime'];
    public function graduation() { return $this->belongsTo(Graduation::class); }
}

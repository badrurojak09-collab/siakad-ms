<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ThesisExaminer extends Model
{
    use SoftDeletes, BelongsToTenant;
    protected $guarded = [];
    protected $casts = ['assigned_at' => 'datetime'];
    public function thesis() { return $this->belongsTo(Thesis::class); }
    public function lecturer() { return $this->belongsTo(Lecturer::class); }
}

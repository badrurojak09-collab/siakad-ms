<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Faculty extends Model
{
    use SoftDeletes, BelongsToTenant;

    protected $table = 'faculties';
    protected $guarded = [];
    protected $casts = ['metadata' => 'array'];

    public function dean(): BelongsTo { return $this->belongsTo(Lecturer::class, 'dean_id'); }
    public function departments(): HasMany { return $this->hasMany(Department::class); }
}

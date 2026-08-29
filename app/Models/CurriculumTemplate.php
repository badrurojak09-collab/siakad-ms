<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CurriculumTemplate extends Model
{
    use SoftDeletes, BelongsToTenant;

    protected $table = 'curriculum_templates';
    protected $guarded = [];
    protected $casts = ['metadata' => 'array', 'entry_year' => 'integer', 'max_sks_per_semester' => 'integer', 'min_sks_per_semester' => 'integer', 'total_credits_required' => 'integer'];

    public function curriculum(): BelongsTo { return $this->belongsTo(Curriculum::class); }
}

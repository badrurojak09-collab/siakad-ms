<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeeType extends Model
{
    use SoftDeletes, BelongsToTenant;

    protected $guarded = [];
    protected $casts = ['default_amount' => 'decimal:2', 'is_active' => 'boolean', 'metadata' => 'array'];

    public function bills(): HasMany { return $this->hasMany(AcademicBill::class); }
    public function admissionBills(): HasMany { return $this->hasMany(AdmissionBill::class); }
}

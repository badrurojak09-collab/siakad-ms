<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes, BelongsToTenant;

    protected $guarded = [];
    protected $casts = ['amount' => 'decimal:2', 'paid_at' => 'datetime', 'metadata' => 'array'];

    public function bill(): BelongsTo { return $this->belongsTo(AcademicBill::class, 'academic_bill_id'); }
    public function student(): BelongsTo { return $this->belongsTo(Student::class); }
    public function receivedBy(): BelongsTo { return $this->belongsTo(User::class, 'received_by'); }
}

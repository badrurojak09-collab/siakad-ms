<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdmissionDocument extends Model
{
    use SoftDeletes, BelongsToTenant;
    protected $table = 'admission_documents';
    protected $guarded = [];
    protected $casts = ['verified_at' => 'datetime'];
    public function applicant() { return $this->belongsTo(Applicant::class); }
}

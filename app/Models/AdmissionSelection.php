<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdmissionSelection extends Model
{
    use SoftDeletes, BelongsToTenant;
    protected $table = 'admission_selections';
    protected $guarded = [];
    protected $casts = ['score' => 'decimal:2'];
    public function applicant() { return $this->belongsTo(Applicant::class); }
    public function studyProgram() { return $this->belongsTo(StudyProgram::class); }
}

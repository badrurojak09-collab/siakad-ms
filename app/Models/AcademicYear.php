<?php
namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcademicYear extends Model
{
    use SoftDeletes, BelongsToTenant;

    protected $table = 'academic_years';
    protected $guarded = [];
    protected $casts = ['metadata' => 'array'];
}

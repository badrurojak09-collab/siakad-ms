<?php
namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use SoftDeletes, BelongsToTenant;

    protected $guarded = [];

    protected $casts = [
        'credits' => 'integer',
        'theory_credits' => 'integer',
        'practice_credits' => 'integer',
        'metadata' => 'array',
    ];

    public function classes()
    {
        return $this->hasMany(CourseClass::class);
    }

    public function prerequisites()
    {
        return $this->hasMany(CoursePrerequisite::class);
    }
}

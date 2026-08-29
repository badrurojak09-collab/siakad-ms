<?php
namespace App\Models;
use App\Models\Concerns\BelongsToTenant; use Illuminate\Database\Eloquent\Model; use Illuminate\Database\Eloquent\SoftDeletes;
class AcademicTranscript extends Model { use SoftDeletes,BelongsToTenant; protected $guarded=[]; protected $casts=['gpa'=>'decimal:2','total_credits'=>'decimal:2','total_quality_points'=>'decimal:2','generated_at'=>'datetime','finalized_at'=>'datetime']; public function student(){return $this->belongsTo(Student::class);} public function semester(){return $this->belongsTo(Semester::class);} public function items(){return $this->hasMany(AcademicTranscriptItem::class,'transcript_id');} }

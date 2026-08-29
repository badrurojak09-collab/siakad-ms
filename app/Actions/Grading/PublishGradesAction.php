<?php
namespace App\Actions\Grading;
use App\Models\GradeSubmission; use Illuminate\Validation\ValidationException;
class PublishGradesAction { public function execute(GradeSubmission $submission,int $approverId): GradeSubmission { if($submission->status!=='submitted') throw ValidationException::withMessages(['status'=>'Nilai hanya dapat dipublish dari status submitted.']); $submission->update(['status'=>'published','approved_by'=>$approverId,'published_at'=>now()]); $submission->courseClass?->grades()->where('grade_status','draft')->update(['grade_status'=>'published','published_at'=>now(),'locked_at'=>now()]); activity('grading')->causedBy($approverId)->performedOn($submission)->log('grades.published'); return $submission->refresh(); } }

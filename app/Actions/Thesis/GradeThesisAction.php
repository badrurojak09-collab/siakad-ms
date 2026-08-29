<?php
namespace App\Actions\Thesis;
use App\Models\{Thesis,ThesisGrade}; use Illuminate\Validation\ValidationException;
class GradeThesisAction { public function execute(Thesis $thesis,array $data,int $graderId): ThesisGrade { if($thesis->status!=='in_progress') throw ValidationException::withMessages(['status'=>'Tugas akhir belum siap dinilai.']); $score=(float)($data['score']??-1); if($score<0||$score>100) throw ValidationException::withMessages(['score'=>'Nilai harus 0 sampai 100.']); return $thesis->thesisGrades()->create(['tenant_id'=>$thesis->tenant_id,'component'=>$data['component']??'final','score'=>$score,'letter_grade'=>$score>=85?'A':($score>=75?'B':($score>=65?'C':($score>=50?'D':'E'))),'grade_by'=>$graderId,'grade_date'=>now()]); } }

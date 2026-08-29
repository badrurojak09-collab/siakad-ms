<?php
namespace App\Actions\Attendance;
use App\Models\{Student,CourseClass};
class EvaluateExamEligibilityAction { public function execute(Student $student,CourseClass $class,float $minimum=75): array { $sessions=$class->attendanceSessions()->with(['records'=>fn($q)=>$q->where('student_id',$student->id)])->get();$total=$sessions->count();$present=$sessions->sum(fn($s)=>(int)$s->records->whereIn('status',['present','late','excused'])->isNotEmpty());$percentage=$total?round($present/$total*100,2):0;return ['eligible'=>$percentage>=$minimum,'percentage'=>$percentage,'present'=>$present,'total'=>$total,'minimum'=>$minimum]; } }

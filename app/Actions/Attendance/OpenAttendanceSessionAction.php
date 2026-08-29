<?php
namespace App\Actions\Attendance;
use App\Models\{AttendanceSession,CourseClass};
use Illuminate\Validation\ValidationException;
class OpenAttendanceSessionAction {
 public function execute(CourseClass $class, string $date, int $meetingNumber, ?string $topic=null): AttendanceSession {
  if($class->status !== 'active') throw ValidationException::withMessages(['class'=>'Kelas belum aktif.']);
  if($meetingNumber < 1 || $meetingNumber > 16) throw ValidationException::withMessages(['meeting_number'=>'Pertemuan harus 1 sampai 16.']);
  if(AttendanceSession::query()->where('course_class_id',$class->getKey())->where('meeting_number',$meetingNumber)->exists()) throw ValidationException::withMessages(['meeting_number'=>'Pertemuan sudah dibuat.']);
  return AttendanceSession::create(['tenant_id'=>$class->tenant_id,'course_class_id'=>$class->getKey(),'meeting_date'=>$date,'meeting_number'=>$meetingNumber,'topic'=>$topic,'opened_at'=>now()]);
 }
}

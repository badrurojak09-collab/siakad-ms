<?php
namespace App\Actions\Attendance;
use App\Models\{AttendanceRecord,AttendanceSession,Student};
use Illuminate\Validation\ValidationException;
class RecordAttendanceAction {
 public function execute(AttendanceSession $session, Student $student, string $status, ?string $notes=null): AttendanceRecord {
  if(!$session->opened_at || $session->closed_at) throw ValidationException::withMessages(['session'=>'Sesi presensi tidak sedang terbuka.']);
  if(!in_array($status,['present','late','excused','absent'],true)) throw ValidationException::withMessages(['status'=>'Status presensi tidak valid.']);
  return AttendanceRecord::updateOrCreate(['attendance_session_id'=>$session->getKey(),'student_id'=>$student->getKey()],['status'=>$status,'check_in_at'=>now(),'notes'=>$notes]);
 }
}

<?php
namespace App\Actions\Attendance;

use App\Models\AttendanceSession;
use Illuminate\Validation\ValidationException;

class CloseAttendanceSessionAction
{
    public function execute(AttendanceSession $session, int $actorId): AttendanceSession
    {
        if ($session->closed_at)
            throw ValidationException::withMessages(['session' => 'Sesi presensi sudah ditutup.']);
        $session->update(['closed_at' => now()]);
        activity('attendance')->causedBy($actorId)->performedOn($session)->log('attendance.session_closed');
        return $session->refresh();
    }
}

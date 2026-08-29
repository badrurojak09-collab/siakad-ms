<?php

namespace App\Actions\Thesis;

use App\Models\{Lecturer, Thesis, ThesisExaminer};
use Illuminate\Validation\ValidationException;

class AssignThesisExaminerAction
{
    public function execute(Thesis $thesis, int $lecturerId, string $role = 'examiner'): ThesisExaminer
    {
        if (!in_array($thesis->status, ['in_progress', 'defense_scheduled'], true)) {
            throw ValidationException::withMessages(['status' => 'Penguji hanya dapat ditetapkan pada tugas akhir aktif.']);
        }
        if (!Lecturer::whereKey($lecturerId)->where('tenant_id', $thesis->tenant_id)->exists()) {
            throw ValidationException::withMessages(['lecturer_id' => 'Dosen penguji tidak berada pada tenant yang sama.']);
        }
        if ($thesis->examiners()->where('status', 'assigned')->count() >= 2 && $role === 'examiner') {
            throw ValidationException::withMessages(['examiner' => 'Maksimal dua penguji aktif dapat ditetapkan.']);
        }
        if ($thesis->examiners()->where('lecturer_id', $lecturerId)->where('role', $role)->exists()) {
            throw ValidationException::withMessages(['lecturer_id' => 'Dosen sudah ditetapkan pada tugas akhir ini.']);
        }

        $examiner = $thesis->examiners()->create([
            'tenant_id' => $thesis->tenant_id,
            'lecturer_id' => $lecturerId,
            'role' => $role,
            'status' => 'assigned',
            'assigned_at' => now(),
        ]);
        activity('thesis')->performedOn($thesis)->withProperties(['examiner_id' => $examiner->id])->log('thesis.examiner_assigned');
        return $examiner;
    }
}

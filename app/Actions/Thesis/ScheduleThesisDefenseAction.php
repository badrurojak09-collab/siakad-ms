<?php

namespace App\Actions\Thesis;

use App\Models\Thesis;
use Illuminate\Validation\ValidationException;

class ScheduleThesisDefenseAction
{
    public function execute(Thesis $thesis, string $defenseDate, string $room): Thesis
    {
        if ($thesis->status !== 'in_progress') {
            throw ValidationException::withMessages(['status' => 'Sidang hanya dapat dijadwalkan untuk tugas akhir berstatus in_progress.']);
        }
        if (!$thesis->supervisor_1_id) {
            throw ValidationException::withMessages(['supervisor' => 'Pembimbing utama wajib ditentukan.']);
        }
        if ($thesis->examiners()->where('status', 'assigned')->count() < 1) {
            throw ValidationException::withMessages(['examiner' => 'Minimal satu penguji aktif wajib ditetapkan.']);
        }
        if (blank($room) || now()->parse($defenseDate)->isPast()) {
            throw ValidationException::withMessages(['defense_date' => 'Tanggal sidang harus di masa mendatang dan ruang wajib diisi.']);
        }
        $thesis->update(['status' => 'defense_scheduled', 'defense_date' => $defenseDate, 'defense_room' => $room]);
        activity('thesis')->performedOn($thesis)->log('thesis.defense_scheduled');
        return $thesis->refresh();
    }
}

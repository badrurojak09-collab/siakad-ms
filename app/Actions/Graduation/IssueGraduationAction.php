<?php

namespace App\Actions\Graduation;

use App\Models\Graduation;
use Illuminate\Validation\ValidationException;

class IssueGraduationAction
{
    public function execute(Graduation $graduation, string $decreeNumber, int $actorId): Graduation
    {
        if ($graduation->status !== 'approved') {
            throw ValidationException::withMessages(['status' => 'Kelulusan hanya dapat diterbitkan setelah yudisium approved.']);
        }
        if (blank(trim($decreeNumber))) {
            throw ValidationException::withMessages(['decree_number' => 'Nomor SK kelulusan wajib diisi.']);
        }
        $graduation->update(['status' => 'issued', 'decree_number' => trim($decreeNumber), 'decree_date' => now()->toDateString(), 'approved_by' => $actorId]);
        activity('graduation')->causedBy($actorId)->performedOn($graduation)->log('graduation.issued');
        return $graduation->refresh();
    }
}

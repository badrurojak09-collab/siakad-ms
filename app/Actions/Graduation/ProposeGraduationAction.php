<?php

namespace App\Actions\Graduation;

use App\Models\Graduation;
use Illuminate\Validation\ValidationException;

class ProposeGraduationAction
{
    public function execute(Graduation $graduation, ?int $actorId = null): Graduation
    {
        if (!in_array($graduation->status, ['proposed', 'eligible'], true)) {
            throw ValidationException::withMessages(['status' => 'Yudisium sudah diproses.']);
        }
        if ((float) $graduation->gpa_final < 2.00) {
            throw ValidationException::withMessages(['gpa_final' => 'IPK minimum untuk yudisium adalah 2,00.']);
        }
        $graduation->update(['status' => 'approved', 'approved_at' => now(), 'approved_by' => $actorId]);
        $activity = activity('graduation')->performedOn($graduation);
        if ($actorId) $activity->causedBy($actorId);
        $activity->log('graduation.approved');
        return $graduation->refresh();
    }
}

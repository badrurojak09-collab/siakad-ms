<?php

namespace App\Actions\Krs;

use App\Models\KrsHeader;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ApproveKrsAction
{
    public function execute(KrsHeader $krs, int $approverId, ?string $notes = null): KrsHeader
    {
        return DB::transaction(function () use ($krs, $approverId, $notes) {
            if ($krs->status !== 'submitted') {
                throw ValidationException::withMessages(['status' => 'KRS hanya dapat disetujui dari status submitted.']);
            }
            $previousStatus = $krs->status;
            $krs->update([
                'status' => 'approved',
                'advisor_approved_at' => now(),
                'approved_by' => $approverId,
                'notes' => $notes ?? $krs->notes,
            ]);
            $krs->logs()->create([
                'previous_status' => $previousStatus,
                'new_status' => 'approved',
                'changed_by' => $approverId,
                'changed_at' => now(),
                'reason' => $notes,
            ]);
            activity('krs')->causedBy($approverId)->performedOn($krs)->log('krs.approved');
            return $krs->refresh();
        });
    }
}

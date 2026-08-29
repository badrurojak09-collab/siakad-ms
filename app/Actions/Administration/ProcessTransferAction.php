<?php
namespace App\Actions\Administration;

use App\Models\Transfer;
use Illuminate\Validation\ValidationException;

class ProcessTransferAction
{
    public function execute(Transfer $transfer, int $approverId, bool $approve = true): Transfer
    {
        if ($transfer->status !== 'pending')
            throw ValidationException::withMessages(['status' => 'Mutasi harus berstatus pending.']);
        if ($approve && $transfer->from_study_program_id === $transfer->to_study_program_id)
            throw ValidationException::withMessages(['study_program' => 'Program studi asal dan tujuan harus berbeda.']);
        $transfer->update(['status' => $approve ? 'approved' : 'rejected', 'approved_by' => $approverId, 'approved_at' => now()]);
        activity('administration')->causedBy($approverId)->performedOn($transfer)->log($approve ? 'transfer.approved' : 'transfer.rejected');
        return $transfer->refresh();
    }
}

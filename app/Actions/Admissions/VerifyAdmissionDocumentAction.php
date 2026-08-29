<?php

namespace App\Actions\Admissions;

use App\Models\AdmissionDocument;
use Illuminate\Validation\ValidationException;

class VerifyAdmissionDocumentAction
{
    public function execute(AdmissionDocument $document, bool $approved, int $actorId, ?string $reason = null): AdmissionDocument
    {
        if (!in_array($document->verification_status, ['pending', 'revision_required'], true)) {
            throw ValidationException::withMessages(['status' => 'Dokumen sudah diverifikasi.']);
        }
        $document->update([
            'verification_status' => $approved ? 'verified' : 'rejected',
            'verified_by' => $actorId,
            'verified_at' => now(),
        ]);
        activity('pmb')->causedBy($actorId)->performedOn($document)
            ->withProperties(['reason' => $reason])
            ->log($approved ? 'document.verified' : 'document.rejected');
        return $document->refresh();
    }
}

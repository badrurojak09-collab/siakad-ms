<?php

namespace App\Actions\Graduation;

use App\Models\{Graduation, GraduationDocument};
use Illuminate\Validation\ValidationException;

class GenerateGraduationDocumentAction
{
    public function execute(Graduation $graduation, string $documentType, int $actorId, ?string $fileUrl = null): GraduationDocument
    {
        if (!in_array($graduation->status, ['approved', 'issued'], true)) {
            throw ValidationException::withMessages(['status' => 'Dokumen hanya dapat diterbitkan untuk yudisium approved.']);
        }
        if (!in_array($documentType, ['decree', 'graduation_letter', 'transcript'], true)) {
            throw ValidationException::withMessages(['document_type' => 'Jenis dokumen tidak terdaftar.']);
        }
        $document = $graduation->documents()->firstOrNew(['document_type' => $documentType]);
        if ($document->exists && $document->verification_hash) {
            throw ValidationException::withMessages(['document_type' => 'Dokumen jenis ini sudah diterbitkan.']);
        }
        $payload = implode('|', [$graduation->tenant_id, $graduation->id, $graduation->student_id, $graduation->decree_number, $documentType, $graduation->gpa_final]);
        $document->fill(['tenant_id' => $graduation->tenant_id, 'file_url' => $fileUrl, 'verification_hash' => hash_hmac('sha256', $payload, (string) config('app.key')), 'generated_by' => $actorId, 'generated_at' => now(), 'metadata' => ['document_type' => $documentType]]);
        $document->save();
        activity('graduation')->causedBy($actorId)->performedOn($document)->log('graduation.document_generated');
        return $document->refresh();
    }

    public function verify(GraduationDocument $document): bool
    {
        $graduation = $document->graduation;
        $payload = implode('|', [$graduation->tenant_id, $graduation->id, $graduation->student_id, $graduation->decree_number, $document->document_type, $graduation->gpa_final]);
        return filled($document->verification_hash) && hash_equals($document->verification_hash, hash_hmac('sha256', $payload, (string) config('app.key')));
    }
}

<?php

namespace App\Actions\Thesis;

use App\Models\{Thesis, ThesisRevision};
use Illuminate\Validation\ValidationException;

class SubmitThesisRevisionAction
{
    public function execute(Thesis $thesis, string $description): ThesisRevision
    {
        if (!in_array($thesis->status, ['defense_scheduled', 'in_progress'], true)) {
            throw ValidationException::withMessages(['status' => 'Revisi hanya dapat diajukan setelah tugas akhir aktif atau sidang terjadwal.']);
        }
        if (blank(trim($description))) {
            throw ValidationException::withMessages(['description' => 'Deskripsi revisi wajib diisi.']);
        }
        $revisionNo = ((int) $thesis->revisions()->max('revision_no')) + 1;
        return $thesis->revisions()->create([
            'tenant_id' => $thesis->tenant_id,
            'revision_no' => $revisionNo,
            'description' => trim($description),
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);
    }
}

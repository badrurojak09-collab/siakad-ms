<?php

namespace App\Actions\Thesis;

use App\Models\{Thesis, ThesisRevision};
use Illuminate\Validation\ValidationException;

class ApproveThesisRevisionAction
{
    public function execute(ThesisRevision $revision, int $actorId): ThesisRevision
    {
        if ($revision->status !== 'submitted') {
            throw ValidationException::withMessages(['status' => 'Revisi hanya dapat disetujui satu kali dari status submitted.']);
        }
        $revision->update(['status' => 'approved', 'approved_at' => now(), 'approved_by' => $actorId]);
        $thesis = $revision->thesis()->with('revisions')->firstOrFail();
        if ($thesis->revisions->isNotEmpty() && $thesis->revisions->every(fn (ThesisRevision $item): bool => $item->status === 'approved')) {
            $thesis->update(['status' => 'completed']);
            activity('thesis')->causedBy($actorId)->performedOn($thesis)->log('thesis.completed');
        }
        activity('thesis')->causedBy($actorId)->performedOn($revision)->log('thesis.revision_approved');
        return $revision->refresh();
    }
}

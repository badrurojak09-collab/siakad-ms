<?php

namespace App\Actions\Reporting;

use App\Actions\Grading\SignAcademicTranscriptAction;
use App\Models\AcademicTranscript;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\Response;

class ExportAcademicTranscriptPdfAction
{
    public function execute(AcademicTranscript $transcript, int $actorId): Response
    {
        $transcript->load(['student', 'semester', 'items.course']);
        abort_unless($transcript->status === 'final', 422, 'Hanya transkrip final yang dapat diekspor.');

        $verification = app(SignAcademicTranscriptAction::class)->verify($transcript);
        $pdf = Pdf::loadView('exports.transcripts.academic', [
            'transcript' => $transcript,
            'items' => $transcript->items->sortBy('id'),
            'signatureValid' => $verification,
        ])->setPaper('a4', 'portrait');

        activity('academic')->causedBy($actorId)->performedOn($transcript)->withProperties([
            'format' => 'pdf',
            'signature_valid' => $verification,
        ])->log('transcript.exported');

        return $pdf->download($this->filename($transcript, 'pdf'));
    }

    private function filename(AcademicTranscript $transcript, string $extension): string
    {
        return 'transkrip-' . $transcript->student_id . '-' . $transcript->id . '.' . $extension;
    }
}

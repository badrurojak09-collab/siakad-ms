<?php

namespace App\Actions\Grading;

use App\Models\{AcademicTranscript, User};
use Illuminate\Validation\ValidationException;

class SignAcademicTranscriptAction
{
    public function execute(AcademicTranscript $transcript, int $actorId, ?string $signerName = null, ?string $signerTitle = null): AcademicTranscript
    {
        if ($transcript->status !== 'final') {
            throw ValidationException::withMessages(['status' => 'Transkrip harus berstatus final sebelum ditandatangani.']);
        }

        $payload = $this->canonicalPayload($transcript->loadMissing(['student', 'semester', 'items.course']));
        $key = (string) config('app.key');
        $hash = hash_hmac('sha256', $payload, $key);

        $transcript->update([
            'signature_algorithm' => 'HMAC-SHA256',
            'signature_hash' => $hash,
            'signed_by' => $actorId,
            'signed_at' => now(),
            'signer_name' => $signerName ?: (string) (User::find($actorId)?->name ?: 'Penandatangan Akademik'),
            'signer_title' => $signerTitle,
        ]);

        activity('academic')->causedBy($actorId)->performedOn($transcript)->withProperties([
            'algorithm' => 'HMAC-SHA256',
            'signature_hash' => $hash,
        ])->log('transcript.signed');

        return $transcript->refresh();
    }

    public function verify(AcademicTranscript $transcript): bool
    {
        if (!$transcript->signature_hash || $transcript->signature_algorithm !== 'HMAC-SHA256') {
            return false;
        }

        $expected = hash_hmac('sha256', $this->canonicalPayload($transcript->loadMissing(['student', 'semester', 'items.course'])), (string) config('app.key'));

        return hash_equals($transcript->signature_hash, $expected);
    }

    public function canonicalPayload(AcademicTranscript $transcript): string
    {
        $items = $transcript->items->sortBy('id')->map(fn ($item): array => [
            'id' => $item->id,
            'course_id' => $item->course_id,
            'course_code' => $item->course?->code,
            'course_name' => $item->course?->name,
            'credits' => (string) $item->credits,
            'score' => (string) $item->score,
            'letter_grade' => $item->letter_grade,
            'grade_point' => (string) $item->grade_point,
            'quality_points' => (string) $item->quality_points,
        ])->values()->all();

        return json_encode([
            'transcript_id' => $transcript->id,
            'tenant_id' => $transcript->tenant_id,
            'student_id' => $transcript->student_id,
            'type' => $transcript->type,
            'semester_id' => $transcript->semester_id,
            'total_credits' => (string) $transcript->total_credits,
            'total_quality_points' => (string) $transcript->total_quality_points,
            'gpa' => (string) $transcript->gpa,
            'status' => $transcript->status,
            'items' => $items,
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRESERVE_ZERO_FRACTION);
    }
}

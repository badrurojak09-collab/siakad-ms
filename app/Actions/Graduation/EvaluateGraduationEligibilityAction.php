<?php

namespace App\Actions\Graduation;

use App\Models\{AcademicBill, AcademicTranscript, Graduation, Thesis};
use Illuminate\Validation\ValidationException;

class EvaluateGraduationEligibilityAction
{
    public function execute(Graduation $graduation): Graduation
    {
        if (!in_array($graduation->status, ['proposed', 'eligible'], true)) {
            throw ValidationException::withMessages(['status' => 'Eligibility hanya dapat dievaluasi sebelum yudisium disetujui.']);
        }
        $transcript = AcademicTranscript::where('tenant_id', $graduation->tenant_id)
            ->where('student_id', $graduation->student_id)->where('type', 'transcript')->where('status', 'final')->latest('id')->first();
        if (!$transcript) {
            throw ValidationException::withMessages(['transcript' => 'Transkrip final mahasiswa belum tersedia.']);
        }
        $thesis = Thesis::where('tenant_id', $graduation->tenant_id)->where('student_id', $graduation->student_id)->where('status', 'completed')->latest('id')->first();
        if (!$thesis) {
            throw ValidationException::withMessages(['thesis' => 'Tugas akhir belum berstatus completed.']);
        }
        $unpaid = AcademicBill::where('tenant_id', $graduation->tenant_id)->where('student_id', $graduation->student_id)->whereIn('status', ['unpaid', 'partial', 'overdue'])->exists();
        if ($unpaid) {
            throw ValidationException::withMessages(['billing' => 'Masih terdapat tagihan akademik yang belum lunas.']);
        }
        if ((float) $transcript->gpa < 2.00) {
            throw ValidationException::withMessages(['gpa_final' => 'IPK minimum untuk eligibility yudisium adalah 2,00.']);
        }
        $graduation->update([
            'status' => 'eligible',
            'gpa_final' => $transcript->gpa,
            'metadata' => array_merge($graduation->metadata ?: [], ['eligibility' => ['transcript_id' => $transcript->id, 'thesis_id' => $thesis->id, 'evaluated_at' => now()->toIso8601String(), 'billing_clear' => true]]),
        ]);
        activity('graduation')->performedOn($graduation)->log('graduation.eligibility_confirmed');
        return $graduation->refresh();
    }
}

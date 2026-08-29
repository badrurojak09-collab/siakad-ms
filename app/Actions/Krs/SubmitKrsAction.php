<?php

namespace App\Actions\Krs;

use App\Models\KrsHeader;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SubmitKrsAction
{
    public function execute(KrsHeader $krs): KrsHeader
    {
        return DB::transaction(function () use ($krs) {
            if ($krs->status !== 'draft' && $krs->status !== 'revision_required') {
                throw ValidationException::withMessages(['status' => 'KRS hanya dapat disubmit dari status draft atau revision_required.']);
            }

            $semester = $krs->semester()->first();
            if (! $semester || ! $semester->is_active) {
                throw ValidationException::withMessages(['semester' => 'Semester KRS belum aktif.']);
            }
            if ($semester->krs_start_date && now()->toDateString() < $semester->krs_start_date->toDateString()) {
                throw ValidationException::withMessages(['semester' => 'Periode pengisian KRS belum dimulai.']);
            }
            if ($semester->krs_end_date && now()->toDateString() > $semester->krs_end_date->toDateString()) {
                throw ValidationException::withMessages(['semester' => 'Periode pengisian KRS sudah berakhir.']);
            }

            $krs->loadMissing('details.courseClass.course');
            $totalCredits = (int) $krs->details->where('status', 'registered')->sum(fn ($detail) => $detail->courseClass?->course?->credits ?? 0);
            if ($totalCredits < 1 || $totalCredits > 24) {
                throw ValidationException::withMessages(['total_credits' => 'Total KRS harus berada antara 1 dan 24 SKS.']);
            }

            $krs->update(['total_credits' => $totalCredits, 'status' => 'submitted', 'submitted_at' => now()]);
            $krs->logs()->create(['previous_status' => 'draft', 'new_status' => 'submitted', 'changed_by' => auth()->id(), 'changed_at' => now()]);
            activity('krs')->performedOn($krs)->withProperties(['total_credits' => $totalCredits])->log('krs.submitted');
            return $krs->refresh();
        });
    }
}

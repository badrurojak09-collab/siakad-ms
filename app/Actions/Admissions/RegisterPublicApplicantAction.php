<?php

namespace App\Actions\Admissions;

use App\Models\AdmissionPeriod;
use App\Models\Applicant;
use App\Models\StudyProgram;
use App\Services\TenantContext;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RegisterPublicApplicantAction
{
    public function __construct(private TenantContext $context) {}

    /**
     * Mendaftarkan pendaftar mandiri dari halaman publik.
     * Mengikuti alur PMB yang ada: Applicant berstatus draft + AdmissionSelection (pilihan prodi),
     * selanjutnya diverifikasi/dinilai oleh petugas PMB melalui panel admin.
     *
     * @param  array{full_name:string,email:string,phone:?string,identity_number:?string,school_origin:?string,admission_period_id:int,program_choices:array<int,string>}  $data
     * @return array{applicant:Applicant,registration_number:string}
     */
    public function execute(array $data): array
    {
        $tenant = $this->context->require();

        $period = AdmissionPeriod::query()
            ->whereKey($data['admission_period_id'])
            ->firstOrFail();

        $this->assertPeriodOpen($period);

        $programs = StudyProgram::query()
            ->whereKey($data['program_choices'])
            ->get();

        abort_unless($programs->isNotEmpty(), 422, 'Program studi tidak valid.');

        return DB::transaction(function () use ($tenant, $period, $programs, $data): array {
            $registrationNumber = $this->nextRegistrationNumber($period);

            $applicant = Applicant::create([
                'tenant_id' => $tenant->getKey(),
                'admission_period_id' => $period->getKey(),
                'registration_number' => $registrationNumber,
                'full_name' => $data['full_name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'identity_number' => $data['identity_number'] ?? null,
                'school_origin' => $data['school_origin'] ?? null,
                'status' => 'draft',
            ]);

            foreach ($programs->values() as $index => $program) {
                $applicant->selections()->create([
                    'tenant_id' => $tenant->getKey(),
                    'study_program_id' => $program->getKey(),
                    'choice_order' => $index + 1,
                    'status' => 'pending',
                ]);
            }

            activity('pmb')->performedOn($applicant)->withProperties([
                'source' => 'public_form',
                'period_id' => $period->getKey(),
            ])->log('applicant.self_registered');

            return [
                'applicant' => $applicant,
                'registration_number' => $registrationNumber,
            ];
        });
    }

    private function assertPeriodOpen(AdmissionPeriod $period): void
    {
        $today = now()->startOfDay();

        if ($period->status !== 'open') {
            abort(422, 'Periode pendaftaran belum dibuka.');
        }

        if ($today->lt($period->registration_start) || $today->gt($period->registration_end)) {
            abort(422, 'Periode pendaftaran tidak sedang berjalan.');
        }
    }

    /**
     * Nomor registrasi unik mengikuti format sistem: PMB-{KODE PERIODE}-{URUT}-YMD-RANDOM
     * (kolom `applicants.registration_number` bersifat unique global).
     */
    private function nextRegistrationNumber(AdmissionPeriod $period): string
    {
        $prefix = 'PMB-' . Str::upper(Str::slug($period->code, '')) . '-' . now()->format('Ymd') . '-';

        for ($attempt = 0; $attempt < 5; $attempt++) {
            $candidate = $prefix . Str::upper(Str::random(6));
            if (! Applicant::query()->where('registration_number', $candidate)->exists()) {
                return $candidate;
            }
        }

        return $prefix . Str::upper(Str::random(10));
    }
}

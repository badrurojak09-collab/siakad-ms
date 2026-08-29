<?php

namespace App\Actions\Graduation;

use App\Models\{CeremonyRegistration, Graduation, GraduationCeremony};
use App\Services\TenantContext;
use Illuminate\Validation\ValidationException;

class RegisterCeremonyAction
{
    public function execute(array $data): CeremonyRegistration
    {
        $tenantId = $data['tenant_id'] ?? app(TenantContext::class)->id();
        $ceremony = GraduationCeremony::where('tenant_id', $tenantId)->findOrFail($data['ceremony_id']);
        $graduation = Graduation::where('tenant_id', $ceremony->tenant_id)->where('student_id', $data['student_id'])->latest('id')->first();
        if (!$graduation || $graduation->status !== 'approved') {
            throw ValidationException::withMessages(['graduation' => 'Mahasiswa harus memiliki yudisium approved sebelum mendaftar wisuda.']);
        }
        if (!$ceremony->is_active) {
            throw ValidationException::withMessages(['ceremony' => 'Pendaftaran wisuda belum dibuka.']);
        }
        if ($ceremony->ceremony_date->isPast()) {
            throw ValidationException::withMessages(['ceremony' => 'Tanggal wisuda sudah terlewati.']);
        }
        if ($ceremony->quota && $ceremony->registrations()->where('confirmation_status', '!=', 'cancelled')->count() >= $ceremony->quota) {
            throw ValidationException::withMessages(['ceremony' => 'Kuota wisuda sudah penuh.']);
        }
        if (CeremonyRegistration::where('tenant_id', $ceremony->tenant_id)->where('student_id', $data['student_id'])->where('ceremony_id', $ceremony->id)->exists()) {
            throw ValidationException::withMessages(['student' => 'Mahasiswa sudah terdaftar pada wisuda ini.']);
        }
        return CeremonyRegistration::create(array_merge($data, [
            'tenant_id' => $ceremony->tenant_id,
            'graduation_id' => $graduation->id,
            'registration_date' => now()->toDateString(),
            'payment_status' => $data['payment_status'] ?? 'pending',
            'confirmation_status' => 'pending',
        ]));
    }
}

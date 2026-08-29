<?php

namespace App\Actions\Graduation;

use App\Models\CeremonyRegistration;
use Illuminate\Validation\ValidationException;

class ConfirmCeremonyPaymentAction
{
    public function execute(CeremonyRegistration $registration, int $actorId, ?string $reference = null): CeremonyRegistration
    {
        if ($registration->confirmation_status === 'cancelled') {
            throw ValidationException::withMessages(['status' => 'Pendaftaran wisuda sudah dibatalkan.']);
        }
        if ($registration->payment_status === 'paid' && $registration->confirmation_status === 'confirmed') {
            throw ValidationException::withMessages(['status' => 'Pembayaran wisuda sudah dikonfirmasi.']);
        }
        $metadata = array_merge($registration->metadata ?: [], ['payment_reference' => $reference, 'payment_confirmed_at' => now()->toIso8601String(), 'payment_confirmed_by' => $actorId]);
        $registration->update(['payment_status' => 'paid', 'confirmation_status' => 'confirmed', 'metadata' => $metadata]);
        activity('graduation')->causedBy($actorId)->performedOn($registration)->log('ceremony.payment_confirmed');
        return $registration->refresh();
    }
}

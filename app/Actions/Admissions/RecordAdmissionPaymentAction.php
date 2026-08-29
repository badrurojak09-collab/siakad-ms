<?php
namespace App\Actions\Admissions;

use App\Models\{AdmissionBill, AdmissionPayment};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class RecordAdmissionPaymentAction
{
    public function execute(AdmissionBill $bill, float $amount, string $method, ?string $reference = null, ?int $receivedBy = null): AdmissionPayment
    {
        if ($amount <= 0 || $amount > $bill->outstanding_amount)
            throw ValidationException::withMessages(['amount' => 'Nominal pembayaran tidak valid atau melebihi sisa tagihan.']);
        return DB::transaction(function () use ($bill, $amount, $method, $reference, $receivedBy) {
            $payment = $bill->payments()->create(['tenant_id' => $bill->tenant_id, 'applicant_id' => $bill->applicant_id, 'payment_number' => 'PMBPAY-' . now()->format('Ymd') . '-' . Str::upper(Str::random(8)), 'amount' => $amount, 'method' => $method, 'status' => 'confirmed', 'reference' => $reference, 'paid_at' => now(), 'received_by' => $receivedBy]);
            $paid = (float) $bill->payments()->where('status', 'confirmed')->sum('amount');
            $bill->update(['paid_amount' => $paid, 'status' => $paid >= (float) $bill->amount ? 'paid' : 'partial']);
            activity('pmb')->performedOn($bill)->withProperties(['payment_id' => $payment->id, 'amount' => $amount])->log('admission_payment.confirmed');
            return $payment;
        });
    }
}

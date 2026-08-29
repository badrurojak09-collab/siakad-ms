<?php

namespace App\Filament\Resources\AdmissionPayments\Pages;

use App\Actions\Admissions\RecordAdmissionPaymentAction;
use App\Filament\Resources\AdmissionPayments\AdmissionPaymentResource;
use App\Models\AdmissionBill;
use App\Models\AdmissionPayment;
use Filament\Resources\Pages\CreateRecord;

class CreateAdmissionPayment extends CreateRecord
{
    protected static string $resource = AdmissionPaymentResource::class;

    protected function handleRecordCreation(array $data): AdmissionPayment
    {
        return app(RecordAdmissionPaymentAction::class)->execute(
            AdmissionBill::query()->findOrFail($data['admission_bill_id']),
            (float) $data['amount'],
            $data['method'],
            $data['reference'] ?? null,
            auth()->id(),
        );
    }
}

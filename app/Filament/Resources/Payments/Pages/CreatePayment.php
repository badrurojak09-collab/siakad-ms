<?php

namespace App\Filament\Resources\Payments\Pages;

use App\Actions\Finance\RecordPaymentAction;
use App\Filament\Resources\Payments\PaymentResource;
use App\Models\AcademicBill;
use App\Models\Payment;
use Filament\Resources\Pages\CreateRecord;

class CreatePayment extends CreateRecord
{
    protected static string $resource = PaymentResource::class;

    protected function handleRecordCreation(array $data): Payment
    {
        return app(RecordPaymentAction::class)->execute(
            AcademicBill::query()->findOrFail($data['academic_bill_id']),
            (float) $data['amount'],
            $data['method'],
            $data['reference'] ?? null,
        );
    }
}

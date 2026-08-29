<?php

namespace App\Filament\Resources\AdmissionBills\Pages;

use App\Actions\Admissions\GenerateAdmissionBillAction;
use App\Filament\Resources\AdmissionBills\AdmissionBillResource;
use App\Models\AdmissionBill;
use App\Models\Applicant;
use App\Models\FeeType;
use Filament\Resources\Pages\CreateRecord;

class CreateAdmissionBill extends CreateRecord
{
    protected static string $resource = AdmissionBillResource::class;

    protected function handleRecordCreation(array $data): AdmissionBill
    {
        return app(GenerateAdmissionBillAction::class)->execute(
            Applicant::query()->findOrFail($data['applicant_id']),
            FeeType::query()->findOrFail($data['fee_type_id']),
            $data['purpose'] ?? 'registration',
        );
    }
}

<?php

namespace App\Filament\Resources\AcademicBills\Pages;

use App\Actions\Finance\GenerateAcademicBillAction;
use App\Filament\Resources\AcademicBills\AcademicBillResource;
use App\Models\AcademicBill;
use App\Models\FeeType;
use App\Models\Semester;
use App\Models\Student;
use Filament\Resources\Pages\CreateRecord;

class CreateAcademicBill extends CreateRecord
{
    protected static string $resource = AcademicBillResource::class;

    protected function handleRecordCreation(array $data): AcademicBill
    {
        $bill = app(GenerateAcademicBillAction::class)->execute(
            Student::query()->findOrFail($data['student_id']),
            Semester::query()->findOrFail($data['semester_id']),
            FeeType::query()->findOrFail($data['fee_type_id']),
            (float) $data['subtotal'],
            (float) ($data['discount'] ?? 0),
            $data['due_date'] ?? null,
        );
        $bill->update(['penalty' => $data['penalty'] ?? 0, 'notes' => $data['notes'] ?? null]);
        return $bill;
    }
}

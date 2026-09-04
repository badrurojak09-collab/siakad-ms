<?php
namespace App\Filament\Resources\AdmissionPayments\Pages;

use App\Filament\Resources\AdmissionPayments\AdmissionPaymentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAdmissionPayments extends ListRecords
{
    protected static string $resource = AdmissionPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Tambah Pembayaran'),
        ];
    }
}

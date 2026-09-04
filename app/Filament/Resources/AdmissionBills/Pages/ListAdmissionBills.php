<?php
namespace App\Filament\Resources\AdmissionBills\Pages;

use App\Filament\Resources\AdmissionBills\AdmissionBillResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAdmissionBills extends ListRecords
{
    protected static string $resource = AdmissionBillResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Tambah Tagihan'),
        ];
    }
}

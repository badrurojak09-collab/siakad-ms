<?php
namespace App\Filament\Resources\AcademicBills\Pages;

use App\Filament\Resources\AcademicBills\AcademicBillResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAcademicBills extends ListRecords
{
    protected static string $resource = AcademicBillResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Tambah Tagihan'),
        ];
    }
}

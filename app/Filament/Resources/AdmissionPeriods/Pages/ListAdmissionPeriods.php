<?php
namespace App\Filament\Resources\AdmissionPeriods\Pages;

use App\Filament\Resources\AdmissionPeriods\AdmissionPeriodResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAdmissionPeriods extends ListRecords
{
    protected static string $resource = AdmissionPeriodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Tambah Periode'),
        ];
    }
}

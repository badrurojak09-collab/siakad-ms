<?php
namespace App\Filament\Resources\GeneratedReports\Pages;

use App\Filament\Resources\GeneratedReports\GeneratedReportResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGeneratedReports extends ListRecords
{
    protected static string $resource = GeneratedReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Tambah Laporan'),
        ];
    }
}

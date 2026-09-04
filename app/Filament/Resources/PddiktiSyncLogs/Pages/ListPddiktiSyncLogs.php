<?php
namespace App\Filament\Resources\PddiktiSyncLogs\Pages;

use App\Filament\Resources\PddiktiSyncLogs\PddiktiSyncLogResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPddiktiSyncLogs extends ListRecords
{
    protected static string $resource = PddiktiSyncLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Tambah Log Sinkronisasi'),
        ];
    }
}

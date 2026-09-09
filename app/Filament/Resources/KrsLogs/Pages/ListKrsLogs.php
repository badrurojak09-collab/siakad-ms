<?php

namespace App\Filament\Resources\KrsLogs\Pages;

use App\Filament\Resources\KrsLogs\KrsLogResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\CreateAction;

class ListKrsLogs extends ListRecords
{
    protected static string $resource = KrsLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Tambah Log KRS'),
        ];
    }
}

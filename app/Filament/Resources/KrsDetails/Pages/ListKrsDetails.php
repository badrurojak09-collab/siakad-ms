<?php
namespace App\Filament\Resources\KrsDetails\Pages;

use App\Filament\Resources\KrsDetails\KrsDetailResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKrsDetails extends ListRecords
{
    protected static string $resource = KrsDetailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Tambah Detail KRS'),
        ];
    }
}

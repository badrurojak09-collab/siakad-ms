<?php
namespace App\Filament\Resources\KrsHeaders\Pages;

use App\Filament\Resources\KrsHeaders\KrsHeaderResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKrsHeaders extends ListRecords
{
    protected static string $resource = KrsHeaderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Tambah KRS'),
        ];
    }
}

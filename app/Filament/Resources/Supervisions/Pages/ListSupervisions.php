<?php
namespace App\Filament\Resources\Supervisions\Pages;

use App\Filament\Resources\Supervisions\SupervisionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSupervisions extends ListRecords
{
    protected static string $resource = SupervisionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Tambah Pembimbing'),
        ];
    }
}

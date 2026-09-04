<?php
namespace App\Filament\Resources\Thesiss\Pages;

use App\Filament\Resources\Thesiss\ThesisResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListThesiss extends ListRecords
{
    protected static string $resource = ThesisResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Tambah Tesis'),
        ];
    }
}

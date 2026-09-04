<?php
namespace App\Filament\Resources\Graduations\Pages;

use App\Filament\Resources\Graduations\GraduationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGraduations extends ListRecords
{
    protected static string $resource = GraduationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Tambah Ijazah'),
        ];
    }
}

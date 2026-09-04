<?php
namespace App\Filament\Resources\GraduationCeremonys\Pages;

use App\Filament\Resources\GraduationCeremonys\GraduationCeremonyResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGraduationCeremonys extends ListRecords
{
    protected static string $resource = GraduationCeremonyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Tambah Periode Wisuda'),
        ];
    }
}

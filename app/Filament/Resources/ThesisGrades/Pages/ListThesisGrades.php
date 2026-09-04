<?php
namespace App\Filament\Resources\ThesisGrades\Pages;

use App\Filament\Resources\ThesisGrades\ThesisGradeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListThesisGrades extends ListRecords
{
    protected static string $resource = ThesisGradeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Tambah Nilai Sidang'),
        ];
    }
}

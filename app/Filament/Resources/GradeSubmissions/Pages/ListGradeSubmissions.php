<?php
namespace App\Filament\Resources\GradeSubmissions\Pages;

use App\Filament\Resources\GradeSubmissions\GradeSubmissionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGradeSubmissions extends ListRecords
{
    protected static string $resource = GradeSubmissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Tambah Nilai'),
        ];
    }
}

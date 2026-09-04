<?php
namespace App\Filament\Resources\AcademicTranscripts\Pages;

use App\Filament\Resources\AcademicTranscripts\AcademicTranscriptResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAcademicTranscripts extends ListRecords
{
    protected static string $resource = AcademicTranscriptResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Tambah Transkrip Akademik'),
        ];
    }
}

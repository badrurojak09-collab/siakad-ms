<?php

namespace App\Filament\Resources\GraduationDocuments\Pages;

use App\Filament\Resources\GraduationDocuments\GraduationDocumentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGraduationDocuments extends ListRecords
{
    protected static string $resource = GraduationDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

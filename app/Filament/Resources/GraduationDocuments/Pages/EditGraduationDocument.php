<?php

namespace App\Filament\Resources\GraduationDocuments\Pages;

use App\Filament\Resources\GraduationDocuments\GraduationDocumentResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditGraduationDocument extends EditRecord
{
    protected static string $resource = GraduationDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}

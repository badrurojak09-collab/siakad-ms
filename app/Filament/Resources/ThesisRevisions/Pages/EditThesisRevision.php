<?php

namespace App\Filament\Resources\ThesisRevisions\Pages;

use App\Filament\Resources\ThesisRevisions\ThesisRevisionResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditThesisRevision extends EditRecord
{
    protected static string $resource = ThesisRevisionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}

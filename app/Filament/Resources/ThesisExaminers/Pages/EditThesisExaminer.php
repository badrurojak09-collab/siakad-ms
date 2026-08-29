<?php

namespace App\Filament\Resources\ThesisExaminers\Pages;

use App\Filament\Resources\ThesisExaminers\ThesisExaminerResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditThesisExaminer extends EditRecord
{
    protected static string $resource = ThesisExaminerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}

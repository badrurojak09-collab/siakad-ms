<?php

namespace App\Filament\Resources\CurriculumTemplates\Pages;

use App\Filament\Resources\CurriculumTemplates\CurriculumTemplateResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditCurriculumTemplate extends EditRecord
{
    protected static string $resource = CurriculumTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\CurriculumTemplates\Pages;

use App\Filament\Resources\CurriculumTemplates\CurriculumTemplateResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCurriculumTemplate extends ViewRecord
{
    protected static string $resource = CurriculumTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

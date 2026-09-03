<?php

namespace App\Filament\Resources\CurriculumTemplates\Pages;

use App\Filament\Resources\CurriculumTemplates\CurriculumTemplateResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCurriculumTemplates extends ListRecords
{
    protected static string $resource = CurriculumTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

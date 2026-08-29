<?php

namespace App\Filament\Resources\ThesisExaminers\Pages;

use App\Filament\Resources\ThesisExaminers\ThesisExaminerResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListThesisExaminers extends ListRecords
{
    protected static string $resource = ThesisExaminerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

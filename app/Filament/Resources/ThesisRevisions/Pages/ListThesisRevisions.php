<?php

namespace App\Filament\Resources\ThesisRevisions\Pages;

use App\Filament\Resources\ThesisRevisions\ThesisRevisionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListThesisRevisions extends ListRecords
{
    protected static string $resource = ThesisRevisionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

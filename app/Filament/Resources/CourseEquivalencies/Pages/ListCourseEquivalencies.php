<?php

namespace App\Filament\Resources\CourseEquivalencies\Pages;

use App\Filament\Resources\CourseEquivalencies\CourseEquivalencyResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCourseEquivalencies extends ListRecords
{
    protected static string $resource = CourseEquivalencyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

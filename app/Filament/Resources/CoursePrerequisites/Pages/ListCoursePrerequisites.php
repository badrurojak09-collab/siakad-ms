<?php

namespace App\Filament\Resources\CoursePrerequisites\Pages;

use App\Filament\Resources\CoursePrerequisites\CoursePrerequisiteResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCoursePrerequisites extends ListRecords
{
    protected static string $resource = CoursePrerequisiteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\CurriculumCourses\Pages;

use App\Filament\Resources\CurriculumCourses\CurriculumCourseResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCurriculumCourse extends ViewRecord
{
    protected static string $resource = CurriculumCourseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

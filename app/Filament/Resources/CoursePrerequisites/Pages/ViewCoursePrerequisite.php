<?php

namespace App\Filament\Resources\CoursePrerequisites\Pages;

use App\Filament\Resources\CoursePrerequisites\CoursePrerequisiteResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCoursePrerequisite extends ViewRecord
{
    protected static string $resource = CoursePrerequisiteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

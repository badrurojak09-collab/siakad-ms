<?php

namespace App\Filament\Resources\CourseEquivalencies\Pages;

use App\Filament\Resources\CourseEquivalencies\CourseEquivalencyResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCourseEquivalency extends ViewRecord
{
    protected static string $resource = CourseEquivalencyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

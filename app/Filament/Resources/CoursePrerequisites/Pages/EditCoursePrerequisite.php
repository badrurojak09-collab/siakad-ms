<?php

namespace App\Filament\Resources\CoursePrerequisites\Pages;

use App\Filament\Resources\CoursePrerequisites\CoursePrerequisiteResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditCoursePrerequisite extends EditRecord
{
    protected static string $resource = CoursePrerequisiteResource::class;

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

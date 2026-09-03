<?php

namespace App\Filament\Resources\CurriculumCourses\Pages;

use App\Filament\Resources\CurriculumCourses\CurriculumCourseResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditCurriculumCourse extends EditRecord
{
    protected static string $resource = CurriculumCourseResource::class;

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

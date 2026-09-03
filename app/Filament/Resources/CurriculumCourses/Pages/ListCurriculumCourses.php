<?php

namespace App\Filament\Resources\CurriculumCourses\Pages;

use App\Filament\Resources\CurriculumCourses\CurriculumCourseResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCurriculumCourses extends ListRecords
{
    protected static string $resource = CurriculumCourseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

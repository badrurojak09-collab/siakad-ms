<?php
namespace App\Filament\Resources\CourseClasss\Pages;

use App\Filament\Resources\CourseClasss\CourseClassResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCourseClasss extends ListRecords
{
    protected static string $resource = CourseClassResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Tambah Kelas Kuliah'),
        ];
    }
}

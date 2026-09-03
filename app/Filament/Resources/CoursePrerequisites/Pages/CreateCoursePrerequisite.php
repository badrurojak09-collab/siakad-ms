<?php

namespace App\Filament\Resources\CoursePrerequisites\Pages;

use App\Filament\Resources\CoursePrerequisites\CoursePrerequisiteResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCoursePrerequisite extends CreateRecord
{
    protected static string $resource = CoursePrerequisiteResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['tenant_id'] = filament()->getTenant()?->getKey();

        return $data;
    }
}

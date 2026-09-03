<?php

namespace App\Filament\Resources\CourseEquivalencies\Pages;

use App\Filament\Resources\CourseEquivalencies\CourseEquivalencyResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCourseEquivalency extends CreateRecord
{
    protected static string $resource = CourseEquivalencyResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['tenant_id'] = filament()->getTenant()?->getKey();

        return $data;
    }
}

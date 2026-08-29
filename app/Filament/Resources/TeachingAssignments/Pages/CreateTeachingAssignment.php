<?php

namespace App\Filament\Resources\TeachingAssignments\Pages;

use App\Actions\Teaching\AssignLecturerAction;
use App\Filament\Resources\TeachingAssignments\TeachingAssignmentResource;
use App\Models\CourseClass;
use App\Models\Lecturer;
use App\Models\TeachingAssignment;
use Filament\Resources\Pages\CreateRecord;

class CreateTeachingAssignment extends CreateRecord
{
    protected static string $resource = TeachingAssignmentResource::class;

    protected function handleRecordCreation(array $data): TeachingAssignment
    {
        return app(AssignLecturerAction::class)->execute(
            CourseClass::query()->findOrFail($data['course_class_id']),
            Lecturer::query()->findOrFail($data['lecturer_id']),
            $data['role'] ?? 'primary',
        );
    }
}

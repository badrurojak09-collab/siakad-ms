<?php

namespace App\Filament\Resources\AcademicAdvisors\Pages;

use App\Actions\Academic\AssignAcademicAdvisorAction;
use App\Filament\Resources\AcademicAdvisors\AcademicAdvisorResource;
use App\Models\{AcademicAdvisor, Lecturer, Semester, Student};
use Filament\Resources\Pages\CreateRecord;

class CreateAcademicAdvisor extends CreateRecord
{
    protected static string $resource = AcademicAdvisorResource::class;

    protected function handleRecordCreation(array $data): AcademicAdvisor
    {
        return app(AssignAcademicAdvisorAction::class)->execute(
            Student::query()->findOrFail($data['student_id']),
            Lecturer::query()->findOrFail($data['lecturer_id']),
            Semester::query()->findOrFail($data['semester_id']),
            $data['assigned_date'] ?? null,
            (bool) ($data['is_active'] ?? true),
            auth()->user(),
        );
    }
}

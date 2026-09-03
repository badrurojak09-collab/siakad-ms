<?php

namespace App\Actions\Academic;

use App\Models\{AcademicAdvisor, Lecturer, Semester, Student, User};
use App\Services\TenantContext;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AssignAcademicAdvisorAction
{
    public function execute(
        Student $student,
        Lecturer $lecturer,
        Semester $semester,
        ?string $assignedDate = null,
        bool $isActive = true,
        ?User $actor = null,
    ): AcademicAdvisor {
        $tenantId = app(TenantContext::class)->id();
        $this->ensureSameTenant($tenantId, $student, $lecturer, $semester);

        if ($isActive && AcademicAdvisor::query()
                ->where('student_id', $student->getKey())
                ->where('semester_id', $semester->getKey())
                ->where('is_active', true)
                ->exists()) {
            throw ValidationException::withMessages([
                'student_id' => 'Mahasiswa sudah memiliki pembimbing akademik aktif pada semester tersebut. Nonaktifkan penugasan lama terlebih dahulu.',
            ]);
        }

        return DB::transaction(function () use ($student, $lecturer, $semester, $assignedDate, $isActive, $actor, $tenantId): AcademicAdvisor {
            $advisor = AcademicAdvisor::query()->create([
                'tenant_id' => $tenantId,
                'student_id' => $student->getKey(),
                'lecturer_id' => $lecturer->getKey(),
                'semester_id' => $semester->getKey(),
                'assigned_date' => $assignedDate,
                'is_active' => $isActive,
            ]);

            activity('academic')
                ->causedBy($actor)
                ->performedOn($advisor)
                ->withProperties([
                    'student_id' => $student->getKey(),
                    'lecturer_id' => $lecturer->getKey(),
                    'semester_id' => $semester->getKey(),
                    'is_active' => $isActive,
                ])
                ->log('academic.advisor_assigned');

            return $advisor->refresh();
        });
    }

    private function ensureSameTenant(int $tenantId, Student $student, Lecturer $lecturer, Semester $semester): void
    {
        foreach ([$student, $lecturer, $semester] as $model) {
            if ((int) $model->tenant_id !== $tenantId) {
                throw ValidationException::withMessages([
                    'student_id' => 'Mahasiswa, dosen, dan semester harus berasal dari institusi yang sama.',
                ]);
            }
        }
    }
}

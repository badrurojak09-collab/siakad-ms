<?php

namespace App\Actions\Academic;

use App\Models\{AcademicAdvisor, Lecturer, Semester, Student, User};
use App\Services\TenantContext;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class UpdateAcademicAdvisorAction
{
    public function execute(AcademicAdvisor $advisor, array $data, ?User $actor = null): AcademicAdvisor
    {
        $tenantId = app(TenantContext::class)->id();
        $student = Student::query()->findOrFail($data['student_id']);
        $lecturer = Lecturer::query()->findOrFail($data['lecturer_id']);
        $semester = Semester::query()->findOrFail($data['semester_id']);

        foreach ([$advisor, $student, $lecturer, $semester] as $model) {
            if ((int) $model->tenant_id !== $tenantId) {
                throw ValidationException::withMessages([
                    'student_id' => 'Seluruh data harus berasal dari institusi yang sama.',
                ]);
            }
        }

        $isActive = (bool) ($data['is_active'] ?? false);
        if ($isActive && AcademicAdvisor::query()
                ->where($advisor->getQualifiedKeyName(), '!=', $advisor->getKey())
                ->where('student_id', $student->getKey())
                ->where('semester_id', $semester->getKey())
                ->where('is_active', true)
                ->exists()) {
            throw ValidationException::withMessages([
                'student_id' => 'Mahasiswa sudah memiliki pembimbing akademik aktif pada semester tersebut.',
            ]);
        }

        return DB::transaction(function () use ($advisor, $student, $lecturer, $semester, $data, $isActive, $actor): AcademicAdvisor {
            $before = $advisor->only(['student_id', 'lecturer_id', 'semester_id', 'assigned_date', 'is_active']);
            $advisor->update([
                'student_id' => $student->getKey(),
                'lecturer_id' => $lecturer->getKey(),
                'semester_id' => $semester->getKey(),
                'assigned_date' => $data['assigned_date'] ?? null,
                'is_active' => $isActive,
            ]);

            activity('academic')
                ->causedBy($actor)
                ->performedOn($advisor)
                ->withProperties(['before' => $before, 'after' => $advisor->only(array_keys($before))])
                ->log('academic.advisor_updated');

            return $advisor->refresh();
        });
    }
}

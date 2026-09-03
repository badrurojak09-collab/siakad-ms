<?php

namespace Tests\Feature\Academic;

use App\Actions\Academic\AssignAcademicAdvisorAction;
use App\Models\{AcademicAdvisor, AcademicYear, Lecturer, Semester, Student, Tenant, User};
use App\Services\TenantContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class AcademicAdvisorTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_have_only_one_active_advisor_in_a_semester(): void
    {
        $tenant = Tenant::create(['code' => 'ADV', 'name' => 'Tenant Advisor', 'status' => 'active']);
        app(TenantContext::class)->set($tenant);

        $student = Student::create(['tenant_id' => $tenant->id, 'nim' => 'ADV-001']);
        $lecturerOne = Lecturer::create(['tenant_id' => $tenant->id, 'nidn' => '100001']);
        $lecturerTwo = Lecturer::create(['tenant_id' => $tenant->id, 'nidn' => '100002']);
        $year = AcademicYear::create(['tenant_id' => $tenant->id, 'year_code' => '2026', 'start_date' => '2026-01-01', 'end_date' => '2026-12-31']);
        $semester = Semester::create(['tenant_id' => $tenant->id, 'academic_year_id' => $year->id, 'semester_type' => 'odd', 'start_date' => '2026-08-01', 'end_date' => '2026-12-31']);

        app(AssignAcademicAdvisorAction::class)->execute($student, $lecturerOne, $semester, '2026-08-01');

        $this->expectException(ValidationException::class);
        app(AssignAcademicAdvisorAction::class)->execute($student, $lecturerTwo, $semester, '2026-08-02');
    }

    public function test_advisor_assignment_rejects_cross_tenant_records(): void
    {
        $tenant = Tenant::create(['code' => 'ADV-A', 'name' => 'Tenant A', 'status' => 'active']);
        $otherTenant = Tenant::create(['code' => 'ADV-B', 'name' => 'Tenant B', 'status' => 'active']);
        app(TenantContext::class)->set($tenant);

        $student = Student::create(['tenant_id' => $tenant->id, 'nim' => 'ADV-A-001']);
        $lecturer = Lecturer::create(['tenant_id' => $otherTenant->id, 'nidn' => '200001']);
        $year = AcademicYear::create(['tenant_id' => $tenant->id, 'year_code' => '2027', 'start_date' => '2027-01-01', 'end_date' => '2027-12-31']);
        $semester = Semester::create(['tenant_id' => $tenant->id, 'academic_year_id' => $year->id, 'semester_type' => 'odd', 'start_date' => '2027-08-01', 'end_date' => '2027-12-31']);

        $this->expectException(ValidationException::class);
        app(AssignAcademicAdvisorAction::class)->execute($student, $lecturer, $semester);
    }
}

<?php

namespace Tests\Feature\Graduation;

use App\Actions\Graduation\{ConfirmCeremonyPaymentAction, EvaluateGraduationEligibilityAction, GenerateGraduationDocumentAction, IssueGraduationAction, ProposeGraduationAction};
use App\Actions\Pddikti\DispatchPddiktiSyncAction;
use App\Actions\Thesis\{ApproveThesisRevisionAction, AssignThesisExaminerAction, ScheduleThesisDefenseAction, SubmitThesisRevisionAction, SubmitThesisAction};
use App\Jobs\SyncToPddikti;
use App\Models\{AcademicTranscript, AcademicYear, CeremonyRegistration, Graduation, GraduationCeremony, Lecturer, Semester, Student, Thesis, Tenant, User};
use App\Services\TenantContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class PhaseFGHWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tenant = Tenant::create(['code' => 'FGH', 'name' => 'FGH Tenant', 'status' => 'active']);
        $this->actor = User::create(['name' => 'FGH Actor', 'email' => 'fgh@example.test', 'password' => 'password']);
        app(TenantContext::class)->set($this->tenant);
        $this->student = Student::create(['tenant_id' => $this->tenant->id, 'nim' => 'FGH-001', 'entry_year' => 2020, 'status' => 'active']);
        $year = AcademicYear::create(['tenant_id' => $this->tenant->id, 'year_code' => 'FGH', 'start_date' => '2020-01-01', 'end_date' => '2026-12-31', 'is_active' => true]);
        $this->semester = Semester::create(['tenant_id' => $this->tenant->id, 'academic_year_id' => $year->id, 'semester_type' => 'odd', 'start_date' => '2026-08-01', 'end_date' => '2026-12-31', 'is_active' => true]);
        $this->lecturer = Lecturer::create(['tenant_id' => $this->tenant->id, 'nidn' => 'FGH-LECT-1']);
        $this->thesis = Thesis::create(['tenant_id' => $this->tenant->id, 'student_id' => $this->student->id, 'title' => 'Thesis FGH', 'status' => 'proposed', 'supervisor_1_id' => $this->lecturer->id]);
    }

    public function test_thesis_defense_revision_and_graduation_eligibility_flow(): void
    {
        app(SubmitThesisAction::class)->execute($this->thesis);
        app(AssignThesisExaminerAction::class)->execute($this->thesis->refresh(), $this->lecturer->id);
        app(ScheduleThesisDefenseAction::class)->execute($this->thesis->refresh(), now()->addDays(5)->toDateTimeString(), 'R-101');
        $revision = app(SubmitThesisRevisionAction::class)->execute($this->thesis->refresh(), 'Perbaiki metodologi dan lampiran.');
        app(ApproveThesisRevisionAction::class)->execute($revision, $this->actor->id);
        $this->assertSame('completed', $this->thesis->refresh()->status);

        AcademicTranscript::create(['tenant_id' => $this->tenant->id, 'student_id' => $this->student->id, 'type' => 'transcript', 'semester_id' => $this->semester->id, 'total_credits' => 144, 'total_quality_points' => 468, 'gpa' => 3.25, 'status' => 'final']);
        $graduation = Graduation::create(['tenant_id' => $this->tenant->id, 'student_id' => $this->student->id, 'semester_id' => $this->semester->id, 'graduation_date' => now()->addMonth()->toDateString(), 'status' => 'proposed']);
        app(EvaluateGraduationEligibilityAction::class)->execute($graduation);
        $approved = app(ProposeGraduationAction::class)->execute($graduation->refresh());
        $issued = app(IssueGraduationAction::class)->execute($approved->refresh(), 'SK-001/FGH/2026', $this->actor->id);
        $document = app(GenerateGraduationDocumentAction::class)->execute($issued, 'decree', $this->actor->id);

        $this->assertSame('issued', $issued->status);
        $this->assertTrue(app(GenerateGraduationDocumentAction::class)->verify($document));

        $ceremony = GraduationCeremony::create(['tenant_id' => $this->tenant->id, 'name' => 'Wisuda FGH', 'ceremony_date' => now()->addMonths(2)->toDateString(), 'is_active' => true]);
        $registration = CeremonyRegistration::create(['tenant_id' => $this->tenant->id, 'student_id' => $this->student->id, 'graduation_id' => $issued->id, 'ceremony_id' => $ceremony->id, 'payment_status' => 'pending', 'confirmation_status' => 'pending']);
        $paid = app(ConfirmCeremonyPaymentAction::class)->execute($registration, $this->actor->id, 'PAY-001');
        $this->assertSame('confirmed', $paid->confirmation_status);
        $this->assertSame('paid', $paid->payment_status);
    }

    public function test_pddikti_dispatch_is_idempotent(): void
    {
        Queue::fake();
        $first = app(DispatchPddiktiSyncAction::class)->execute('student', (string) $this->student->id, 'upsert', $this->tenant->id);
        $second = app(DispatchPddiktiSyncAction::class)->execute('student', (string) $this->student->id, 'upsert', $this->tenant->id);

        $this->assertSame($first->id, $second->id);
        Queue::assertPushed(SyncToPddikti::class, 1);
    }
}

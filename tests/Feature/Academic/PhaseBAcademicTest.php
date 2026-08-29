<?php

namespace Tests\Feature\Academic;

use App\Actions\Academic\ActivateSemesterAction;
use App\Actions\Finance\RecordPaymentAction;
use App\Actions\Krs\EnrollStudentInClassAction;
use App\Actions\Krs\ValidateKrsEligibilityAction;
use App\Models\{AcademicBill, AcademicYear, Course, CourseClass, FeeType, KrsHeader, Schedule, Semester, Student, Tenant, User};
use App\Services\TenantContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class PhaseBAcademicTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tenant = Tenant::create(['code' => 'B', 'name' => 'Phase B', 'status' => 'active']);
        app(TenantContext::class)->set($this->tenant);
        $this->user = User::create(['name' => 'Admin', 'email' => 'admin@b.com', 'password' => 'pass']);
        $this->student = Student::create(['tenant_id' => $this->tenant->id, 'user_id' => $this->user->id, 'nim' => '123', 'entry_year' => 2026]);
        $this->year = AcademicYear::create(['tenant_id' => $this->tenant->id, 'year_code' => '2026', 'start_date' => '2026-01-01', 'end_date' => '2026-12-31', 'is_active' => true]);
        $this->semester = Semester::create(['tenant_id' => $this->tenant->id, 'academic_year_id' => $this->year->id, 'semester_type' => 'odd', 'start_date' => '2026-08-01', 'end_date' => '2026-12-31', 'is_active' => true, 'krs_start_date' => '2026-08-01', 'krs_end_date' => '2026-08-31']);
    }

    public function test_krs_eligibility_calculates_max_sks_based_on_gpa(): void
    {
        $result = app(ValidateKrsEligibilityAction::class)->execute($this->student, $this->semester);
        $this->assertEquals(0.0, $result['gpa']);
        $this->assertEquals(15, $result['max_sks']);
    }

    public function test_krs_enrollment_detects_schedule_conflict(): void
    {
        $c1 = Course::create(['tenant_id' => $this->tenant->id, 'code' => 'C1', 'name' => 'C1', 'credits' => 3]);
        $c2 = Course::create(['tenant_id' => $this->tenant->id, 'code' => 'C2', 'name' => 'C2', 'credits' => 3]);
        $cl1 = CourseClass::create(['tenant_id' => $this->tenant->id, 'course_id' => $c1->id, 'semester_id' => $this->semester->id, 'class_code' => 'A', 'status' => 'active', 'capacity' => 40]);
        $cl2 = CourseClass::create(['tenant_id' => $this->tenant->id, 'course_id' => $c2->id, 'semester_id' => $this->semester->id, 'class_code' => 'B', 'status' => 'active', 'capacity' => 40]);
        
        Schedule::create(['tenant_id' => $this->tenant->id, 'course_class_id' => $cl1->id, 'day_of_week' => 1, 'start_time' => '08:00', 'end_time' => '10:00']);
        Schedule::create(['tenant_id' => $this->tenant->id, 'course_class_id' => $cl2->id, 'day_of_week' => 1, 'start_time' => '09:00', 'end_time' => '11:00']);

        $header = KrsHeader::create(['tenant_id' => $this->tenant->id, 'student_id' => $this->student->id, 'semester_id' => $this->semester->id, 'status' => 'draft']);
        
        app(EnrollStudentInClassAction::class)->execute($header, $cl1);
        
        $this->expectException(ValidationException::class);
        app(EnrollStudentInClassAction::class)->execute($header, $cl2);
    }

    public function test_payment_updates_bill_status(): void
    {
        $ft = FeeType::create(['tenant_id' => $this->tenant->id, 'name' => 'SPP', 'code' => 'SPP', 'default_amount' => 1000000]);
        $bill = AcademicBill::create(['tenant_id' => $this->tenant->id, 'student_id' => $this->student->id, 'semester_id' => $this->semester->id, 'fee_type_id' => $ft->id, 'bill_number' => 'BILL-001', 'issued_at' => now(), 'due_date' => now()->addMonth(), 'subtotal' => 1000000, 'total' => 1000000, 'paid_amount' => 0, 'status' => 'unpaid']);
        
        app(RecordPaymentAction::class)->execute($bill, 1000000.0, 'transfer');
        
        $this->assertEquals('paid', $bill->refresh()->status);
        $this->assertEquals(1000000, $bill->paid_amount);
    }
}

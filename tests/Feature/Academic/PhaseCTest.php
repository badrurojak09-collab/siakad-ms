<?php

namespace Tests\Feature\Academic;

use App\Actions\Attendance\EvaluateExamEligibilityAction;
use App\Actions\Grading\CalculateTranscriptAction;
use App\Models\{AcademicTranscript, AcademicYear, AttendanceRecord, AttendanceSession, Course, CourseClass, Semester, Student, StudentGrade, Tenant};
use App\Services\TenantContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PhaseCTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tenant = Tenant::create(['code' => 'C', 'name' => 'Phase C', 'status' => 'active']);
        app(TenantContext::class)->set($this->tenant);
        $this->student = Student::create(['tenant_id' => $this->tenant->id, 'nim' => 'C-001', 'entry_year' => 2026, 'status' => 'active']);
        $year = AcademicYear::create(['tenant_id' => $this->tenant->id, 'year_code' => '2026', 'start_date' => '2026-01-01', 'end_date' => '2026-12-31', 'is_active' => true]);
        $this->semester = Semester::create(['tenant_id' => $this->tenant->id, 'academic_year_id' => $year->id, 'semester_type' => 'odd', 'start_date' => '2026-08-01', 'end_date' => '2026-12-31', 'is_active' => true]);
    }

    public function test_transcript_calculates_weighted_gpa_from_published_grades(): void
    {
        $course = Course::create(['tenant_id' => $this->tenant->id, 'code' => 'C-101', 'name' => 'Course C', 'credits' => 3]);
        $class = CourseClass::create(['tenant_id' => $this->tenant->id, 'course_id' => $course->id, 'semester_id' => $this->semester->id, 'class_code' => 'A', 'status' => 'active', 'capacity' => 40]);
        StudentGrade::create(['tenant_id' => $this->tenant->id, 'student_id' => $this->student->id, 'course_class_id' => $class->id, 'final_score' => 90, 'letter_grade' => 'A', 'grade_point' => 4, 'grade_status' => 'published']);

        $transcript = app(CalculateTranscriptAction::class)->execute($this->student, $this->semester);
        $this->assertInstanceOf(AcademicTranscript::class, $transcript);
        $this->assertEquals(3, $transcript->total_credits);
        $this->assertEquals(4, $transcript->gpa);
        $this->assertCount(1, $transcript->items);
    }

    public function test_exam_eligibility_uses_present_late_and_excused_records(): void
    {
        $course = Course::create(['tenant_id' => $this->tenant->id, 'code' => 'C-102', 'name' => 'Attendance Course', 'credits' => 3]);
        $class = CourseClass::create(['tenant_id' => $this->tenant->id, 'course_id' => $course->id, 'semester_id' => $this->semester->id, 'class_code' => 'A', 'status' => 'active', 'capacity' => 40]);
        for ($i = 1; $i <= 4; $i++) {
            $session = AttendanceSession::create(['tenant_id' => $this->tenant->id, 'course_class_id' => $class->id, 'meeting_date' => "2026-08-0{$i}", 'meeting_number' => $i, 'opened_at' => now()]);
            if ($i < 4) AttendanceRecord::create(['tenant_id' => $this->tenant->id, 'attendance_session_id' => $session->id, 'student_id' => $this->student->id, 'status' => $i === 2 ? 'late' : 'present']);
        }
        $result = app(EvaluateExamEligibilityAction::class)->execute($this->student, $class, 75);
        $this->assertTrue($result['eligible']);
        $this->assertEquals(75, $result['percentage']);
    }
}

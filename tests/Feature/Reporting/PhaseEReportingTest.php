<?php

namespace Tests\Feature\Reporting;

use App\Actions\Grading\{CalculateTranscriptAction, FinalizeTranscriptAction, SignAcademicTranscriptAction};
use App\Actions\Reporting\{BuildAcademicSummaryAction, ExportAcademicTranscriptExcelAction, ExportAcademicTranscriptPdfAction, GenerateAcademicReportAction};
use App\Models\{AcademicTranscript, AcademicYear, Course, CourseClass, Semester, Student, StudentGrade, Tenant, User};
use App\Services\TenantContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PhaseEReportingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tenant = Tenant::create(['code' => 'E', 'name' => 'Phase E', 'status' => 'active']);
        $this->actor = User::create(['name' => 'Report Actor', 'email' => 'report@example.test', 'password' => 'password']);
        app(TenantContext::class)->set($this->tenant);
        $this->student = Student::create(['tenant_id' => $this->tenant->id, 'nim' => 'E-001', 'entry_year' => 2026, 'status' => 'active']);
        $year = AcademicYear::create(['tenant_id' => $this->tenant->id, 'year_code' => '2026', 'start_date' => '2026-01-01', 'end_date' => '2026-12-31', 'is_active' => true]);
        $this->semester = Semester::create(['tenant_id' => $this->tenant->id, 'academic_year_id' => $year->id, 'semester_type' => 'odd', 'start_date' => '2026-08-01', 'end_date' => '2026-12-31', 'is_active' => true]);
    }

    private function createPublishedGrade(int $credits, string $code, string $letter, float $point): void
    {
        $course = Course::create(['tenant_id' => $this->tenant->id, 'code' => $code, 'name' => $code, 'credits' => $credits]);
        $class = CourseClass::create(['tenant_id' => $this->tenant->id, 'course_id' => $course->id, 'semester_id' => $this->semester->id, 'class_code' => $code, 'status' => 'active', 'capacity' => 40]);
        StudentGrade::create(['tenant_id' => $this->tenant->id, 'student_id' => $this->student->id, 'course_class_id' => $class->id, 'final_score' => 90, 'letter_grade' => $letter, 'grade_point' => $point, 'grade_status' => 'published']);
    }

    public function test_summary_and_final_transcript_are_generated(): void
    {
        $this->createPublishedGrade(3, 'E-101', 'A', 4);
        $this->createPublishedGrade(2, 'E-102', 'B', 3);
        $summary = app(BuildAcademicSummaryAction::class)->execute($this->student, $this->semester);
        $this->assertEquals(5, $summary['total_credits']);
        $this->assertEquals(3.6, $summary['gpa']);

        $transcript = app(CalculateTranscriptAction::class)->execute($this->student, $this->semester);
        $final = app(FinalizeTranscriptAction::class)->execute($transcript, $this->actor->id);
        $this->assertEquals('final', $final->status);
        $this->assertNotNull($final->finalized_at);
    }

    public function test_signed_transcript_can_be_verified_and_exported(): void
    {
        $this->createPublishedGrade(3, 'E-301', 'A', 4);
        $transcript = app(CalculateTranscriptAction::class)->execute($this->student, $this->semester);
        app(FinalizeTranscriptAction::class)->execute($transcript, $this->actor->id);
        $signed = app(SignAcademicTranscriptAction::class)->execute($transcript->refresh(), $this->actor->id, 'Dr. Akademik', 'Wakil Rektor');

        $this->assertNotEmpty($signed->signature_hash);
        $this->assertTrue(app(SignAcademicTranscriptAction::class)->verify($signed));
        $signed->update(['gpa' => 0]);
        $this->assertFalse(app(SignAcademicTranscriptAction::class)->verify($signed->refresh()));
        $signed->update(['gpa' => 4]);

        $pdf = app(ExportAcademicTranscriptPdfAction::class)->execute($signed->refresh(), $this->actor->id);
        $this->assertSame('application/pdf', $pdf->headers->get('content-type'));

        $excel = app(ExportAcademicTranscriptExcelAction::class)->execute($signed->refresh(), $this->actor->id);
        $this->assertSame(200, $excel->getStatusCode());
        $this->assertFileExists($excel->getFile()->getPathname());
        @unlink($excel->getFile()->getPathname());
    }

    public function test_academic_report_uses_registered_report_type(): void
    {
        $this->createPublishedGrade(3, 'E-201', 'A', 4);
        $report = app(GenerateAcademicReportAction::class)->execute($this->student, $this->semester, 'semester_recap', $this->actor->id);
        $this->assertEquals('json', $report->file_format);
        $this->assertEquals('semester_recap', $report->metadata['report_type']);
    }
}

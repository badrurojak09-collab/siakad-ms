<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        foreach (['course_prerequisites', 'curriculum_courses', 'curriculum_templates', 'krs_details', 'krs_logs', 'attendance_records'] as $table) {
            if (! Schema::hasColumn($table, 'tenant_id')) {
                Schema::table($table, fn (Blueprint $t) => $t->unsignedBigInteger('tenant_id')->nullable()->index());
            }
        }

        $foreignKeys = [
            ['user_profiles', 'tenant_id', 'tenants', 'id', 'restrict'],
            ['faculties', 'tenant_id', 'tenants', 'id', 'cascade'],
            ['departments', 'tenant_id', 'tenants', 'id', 'cascade'],
            ['study_programs', 'tenant_id', 'tenants', 'id', 'cascade'],
            ['lecturers', 'tenant_id', 'tenants', 'id', 'cascade'],
            ['students', 'tenant_id', 'tenants', 'id', 'cascade'],
            ['courses', 'tenant_id', 'tenants', 'id', 'cascade'],
            ['course_prerequisites', 'tenant_id', 'tenants', 'id', 'cascade'],
            ['academic_years', 'tenant_id', 'tenants', 'id', 'cascade'],
            ['semesters', 'tenant_id', 'tenants', 'id', 'cascade'],
            ['curriculums', 'tenant_id', 'tenants', 'id', 'cascade'],
            ['curriculum_courses', 'tenant_id', 'tenants', 'id', 'cascade'],
            ['curriculum_templates', 'tenant_id', 'tenants', 'id', 'cascade'],
            ['course_classes', 'tenant_id', 'tenants', 'id', 'cascade'],
            ['academic_advisors', 'tenant_id', 'tenants', 'id', 'cascade'],
            ['course_equivalencies', 'tenant_id', 'tenants', 'id', 'cascade'],
            ['krs_headers', 'tenant_id', 'tenants', 'id', 'cascade'],
            ['krs_details', 'tenant_id', 'tenants', 'id', 'cascade'],
            ['krs_logs', 'tenant_id', 'tenants', 'id', 'cascade'],
            ['rooms', 'tenant_id', 'tenants', 'id', 'cascade'],
            ['schedules', 'tenant_id', 'tenants', 'id', 'cascade'],
            ['attendance_sessions', 'tenant_id', 'tenants', 'id', 'cascade'],
            ['attendance_records', 'tenant_id', 'tenants', 'id', 'cascade'],
            ['attendance_corrections', 'tenant_id', 'tenants', 'id', 'cascade'],
            ['assessments', 'tenant_id', 'tenants', 'id', 'cascade'],
            ['student_grades', 'tenant_id', 'tenants', 'id', 'cascade'],
            ['grade_submissions', 'tenant_id', 'tenants', 'id', 'cascade'],
            ['leave_requests', 'tenant_id', 'tenants', 'id', 'cascade'],
            ['transfers', 'tenant_id', 'tenants', 'id', 'cascade'],
            ['mbkm_activities', 'tenant_id', 'tenants', 'id', 'cascade'],
            ['theses', 'tenant_id', 'tenants', 'id', 'cascade'],
            ['supervisions', 'tenant_id', 'tenants', 'id', 'cascade'],
            ['thesis_grades', 'tenant_id', 'tenants', 'id', 'cascade'],
            ['graduations', 'tenant_id', 'tenants', 'id', 'cascade'],
            ['graduation_ceremonies', 'tenant_id', 'tenants', 'id', 'cascade'],
            ['ceremony_registrations', 'tenant_id', 'tenants', 'id', 'cascade'],
            ['pddikti_sync_logs', 'tenant_id', 'tenants', 'id', 'cascade'],
            ['report_definitions', 'tenant_id', 'tenants', 'id', 'cascade'],
            ['generated_reports', 'tenant_id', 'tenants', 'id', 'cascade'],
            ['fee_types', 'tenant_id', 'tenants', 'id', 'cascade'],
            ['academic_bills', 'tenant_id', 'tenants', 'id', 'cascade'],
            ['payments', 'tenant_id', 'tenants', 'id', 'cascade'],
            ['admission_periods', 'tenant_id', 'tenants', 'id', 'cascade'],
            ['applicants', 'tenant_id', 'tenants', 'id', 'cascade'],
            ['admission_selections', 'tenant_id', 'tenants', 'id', 'cascade'],
            ['admission_documents', 'tenant_id', 'tenants', 'id', 'cascade'],
            ['teaching_assignments', 'tenant_id', 'tenants', 'id', 'cascade'],
            ['users', 'id', 'users', 'id', 'restrict'],
            ['user_profiles', 'user_id', 'users', 'id', 'cascade'],
            ['students', 'user_id', 'users', 'id', 'set null'],
            ['lecturers', 'user_id', 'users', 'id', 'set null'],
            ['departments', 'faculty_id', 'faculties', 'id', 'set null'],
            ['study_programs', 'department_id', 'departments', 'id', 'set null'],
            ['students', 'study_program_id', 'study_programs', 'id', 'set null'],
            ['semesters', 'academic_year_id', 'academic_years', 'id', 'set null'],
            ['curriculums', 'study_program_id', 'study_programs', 'id', 'set null'],
            ['curriculum_courses', 'curriculum_id', 'curriculums', 'id', 'cascade'],
            ['curriculum_courses', 'course_id', 'courses', 'id', 'cascade'],
            ['curriculum_templates', 'curriculum_id', 'curriculums', 'id', 'cascade'],
            ['course_prerequisites', 'course_id', 'courses', 'id', 'cascade'],
            ['course_prerequisites', 'prerequisite_course_id', 'courses', 'id', 'cascade'],
            ['course_classes', 'course_id', 'courses', 'id', 'restrict'],
            ['course_classes', 'semester_id', 'semesters', 'id', 'restrict'],
            ['course_classes', 'lecturer_id', 'lecturers', 'id', 'set null'],
            ['course_classes', 'co_lecturer_id', 'lecturers', 'id', 'set null'],
            ['krs_headers', 'student_id', 'students', 'id', 'restrict'],
            ['krs_headers', 'semester_id', 'semesters', 'id', 'restrict'],
            ['krs_details', 'krs_header_id', 'krs_headers', 'id', 'cascade'],
            ['krs_details', 'course_class_id', 'course_classes', 'id', 'restrict'],
            ['krs_logs', 'krs_header_id', 'krs_headers', 'id', 'cascade'],
            ['schedules', 'course_class_id', 'course_classes', 'id', 'cascade'],
            ['schedules', 'room_id', 'rooms', 'id', 'set null'],
            ['schedules', 'lecturer_id', 'lecturers', 'id', 'set null'],
            ['attendance_sessions', 'course_class_id', 'course_classes', 'id', 'cascade'],
            ['attendance_records', 'attendance_session_id', 'attendance_sessions', 'id', 'cascade'],
            ['attendance_records', 'student_id', 'students', 'id', 'cascade'],
            ['student_grades', 'course_class_id', 'course_classes', 'id', 'restrict'],
            ['student_grades', 'student_id', 'students', 'id', 'restrict'],
            ['student_grades', 'assessment_id', 'assessments', 'id', 'set null'],
            ['assessments', 'course_class_id', 'course_classes', 'id', 'cascade'],
            ['grade_submissions', 'course_class_id', 'course_classes', 'id', 'cascade'],
            ['academic_bills', 'student_id', 'students', 'id', 'restrict'],
            ['academic_bills', 'semester_id', 'semesters', 'id', 'restrict'],
            ['academic_bills', 'fee_type_id', 'fee_types', 'id', 'restrict'],
            ['payments', 'academic_bill_id', 'academic_bills', 'id', 'restrict'],
            ['payments', 'student_id', 'students', 'id', 'restrict'],
            ['applicants', 'admission_period_id', 'admission_periods', 'id', 'restrict'],
            ['admission_selections', 'applicant_id', 'applicants', 'id', 'cascade'],
            ['admission_selections', 'study_program_id', 'study_programs', 'id', 'restrict'],
            ['admission_documents', 'applicant_id', 'applicants', 'id', 'cascade'],
            ['teaching_assignments', 'course_class_id', 'course_classes', 'id', 'cascade'],
            ['teaching_assignments', 'lecturer_id', 'lecturers', 'id', 'restrict'],
        ];

        foreach ($foreignKeys as [$table, $column, $refTable, $refColumn, $onDelete]) {
            if ($table === 'users') continue;
            Schema::table($table, function (Blueprint $t) use ($column, $refTable, $refColumn, $onDelete) {
                $constraint = $t->foreign($column)->references($refColumn)->on($refTable);
                match ($onDelete) {
                    'cascade' => $constraint->cascadeOnDelete(),
                    'set null' => $constraint->nullOnDelete(),
                    default => $constraint->restrictOnDelete(),
                };
            });
        }

        Schema::table('tenants', fn (Blueprint $t) => $t->foreign('created_by')->references('id')->on('users')->nullOnDelete());
        Schema::table('tenant_user', fn (Blueprint $t) => $t->index(['tenant_id', 'is_active'], 'tenant_user_active_index'));
        Schema::table('students', fn (Blueprint $t) => $t->unique(['tenant_id', 'nim'], 'students_tenant_nim_unique'));
        Schema::table('lecturers', fn (Blueprint $t) => $t->unique(['tenant_id', 'nidn'], 'lecturers_tenant_nidn_unique'));
        Schema::table('courses', fn (Blueprint $t) => $t->unique(['tenant_id', 'code'], 'courses_tenant_code_unique'));
        Schema::table('course_classes', fn (Blueprint $t) => $t->unique(['tenant_id', 'semester_id', 'class_code'], 'course_classes_tenant_semester_code_unique'));
        Schema::table('schedules', fn (Blueprint $t) => $t->index(['tenant_id', 'course_class_id', 'day_of_week', 'start_time', 'end_time'], 'schedules_conflict_index'));
    }

    public function down(): void
    {
        // Constraint rollback is intentionally kept in a separate deployment step for existing data safety.
    }
};

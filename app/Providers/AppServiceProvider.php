<?php

namespace App\Providers;

use App\Policies\{AcademicPolicy, ActivityLogPolicy, AdmissionsPolicy, AttendancePolicy, FinancePolicy, GraduationPolicy, GradingPolicy, IdentityPolicy, KrsPolicy, PddiktiPolicy, ReportingPolicy, ThesisPolicy, AdministrationPolicy, StudentPolicy};
use Spatie\Permission\Models\{Permission, Role};
use App\Services\Pddikti\{HttpPddiktiClient, NullPddiktiClient, PddiktiClient};
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(\App\Services\TenantContext::class, fn () => new \App\Services\TenantContext());
        $this->app->bind(PddiktiClient::class, fn () => config('services.pddikti.driver', 'null') === 'http' ? new HttpPddiktiClient() : new NullPddiktiClient());
    }

    public function boot(): void
    {
        $map = [
            AcademicPolicy::class => ['AcademicAdvisor', 'AcademicYear', 'Course', 'CourseClass', 'CourseEquivalency', 'CoursePrerequisite', 'Curriculum', 'CurriculumCourse', 'CurriculumTemplate', 'Department', 'Faculty', 'Lecturer', 'Room', 'Schedule', 'Semester', 'StudyProgram', 'TeachingAssignment'],
            IdentityPolicy::class => ['User', 'UserProfile'],
            StudentPolicy::class => ['Student'],
            KrsPolicy::class => ['KrsDetail', 'KrsHeader', 'KrsLog'],
            AttendancePolicy::class => ['AttendanceCorrection', 'AttendanceRecord', 'AttendanceSession'],
            GradingPolicy::class => ['Assessment', 'GradeSubmission', 'StudentGrade', 'ThesisGrade'],
            FinancePolicy::class => ['AcademicBill', 'AdmissionBill', 'AdmissionPayment', 'FeeType', 'Payment'],
            AdmissionsPolicy::class => ['AdmissionPeriod', 'Applicant'],
            ThesisPolicy::class => ['Supervision', 'Thesis', 'ThesisExaminer', 'ThesisRevision'],
            GraduationPolicy::class => ['CeremonyRegistration', 'Graduation', 'GraduationCeremony', 'GraduationDocument'],
            ReportingPolicy::class => ['AcademicTranscript', 'GeneratedReport', 'ReportDefinition'],
            PddiktiPolicy::class => ['PddiktiSyncLog'],
            AdministrationPolicy::class => ['LeaveRequest', 'MbkmActivity', 'Transfer'],
        ];

        Gate::policy(\Spatie\Activitylog\Models\Activity::class, ActivityLogPolicy::class);
        Gate::policy(Role::class, IdentityPolicy::class);
        Gate::policy(Permission::class, IdentityPolicy::class);

        foreach ($map as $policy => $models) {
            foreach ($models as $model) {
                $class = "App\\Models\\{$model}";
                if (class_exists($class)) Gate::policy($class, $policy);
            }
        }

    }
}

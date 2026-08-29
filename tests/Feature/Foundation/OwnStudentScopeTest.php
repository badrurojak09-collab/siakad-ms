<?php

namespace Tests\Feature\Foundation;

use App\Filament\Resources\KrsHeaders\KrsHeaderResource;
use App\Filament\Resources\Students\StudentResource;
use App\Models\{KrsHeader, Student, Tenant, User};
use App\Services\TenantContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\{Permission, Role};
use Tests\TestCase;

class OwnStudentScopeTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_resource_and_krs_are_scoped_to_authenticated_student(): void
    {
        [$tenant, $studentUser, $student, $otherStudent] = $this->fixture();
        app(TenantContext::class)->set($tenant);
        $this->actingAs($studentUser);
        KrsHeader::create(['tenant_id' => $tenant->id, 'student_id' => $student->id, 'total_credits' => 20, 'status' => 'draft']);
        KrsHeader::create(['tenant_id' => $tenant->id, 'student_id' => $otherStudent->id, 'total_credits' => 20, 'status' => 'draft']);

        $this->assertSame([$student->id], StudentResource::getEloquentQuery()->pluck('id')->all());
        $this->assertSame([$student->id], KrsHeaderResource::getEloquentQuery()->pluck('student_id')->all());
    }

    public function test_academic_operator_is_not_restricted_by_own_student_scope(): void
    {
        [$tenant, , $student, $otherStudent] = $this->fixture();
        $operator = User::create(['name' => 'Operator', 'email' => 'operator@example.test', 'password' => 'password']);
        $operator->tenants()->attach($tenant->id);
        $role = Role::create(['name' => 'academic_operator', 'guard_name' => 'web']);
        $role->givePermissionTo(Permission::firstOrCreate(['name' => 'academic.view', 'guard_name' => 'web']));
        $operator->assignRole($role);
        app(TenantContext::class)->set($tenant);
        $this->actingAs($operator);

        $this->assertCount(2, StudentResource::getEloquentQuery()->whereIn('id', [$student->id, $otherStudent->id])->get());
    }

    private function fixture(): array
    {
        $tenant = Tenant::create(['code' => 'SCP', 'name' => 'Scope Tenant', 'status' => 'active']);
        $studentUser = User::create(['name' => 'Student One', 'email' => 'student-one@example.test', 'password' => 'password']);
        $studentUser->tenants()->attach($tenant->id);
        $role = Role::create(['name' => 'student', 'guard_name' => 'web']);
        foreach (['academic.view', 'krs.view'] as $permission) $role->givePermissionTo(Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']));
        $studentUser->assignRole($role);
        $student = Student::create(['tenant_id' => $tenant->id, 'user_id' => $studentUser->id, 'nim' => 'SCP-001', 'entry_year' => 2026, 'status' => 'active']);
        $otherStudent = Student::create(['tenant_id' => $tenant->id, 'nim' => 'SCP-002', 'entry_year' => 2026, 'status' => 'active']);
        return [$tenant, $studentUser, $student, $otherStudent];
    }
}

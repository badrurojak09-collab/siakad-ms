<?php

namespace Tests\Feature\Foundation;

use App\Models\{AcademicBill, Applicant, Graduation, PddiktiSyncLog, Student, Tenant, User};
use App\Services\TenantContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ResourcePolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_domain_policy_requires_permission_and_same_tenant(): void
    {
        $tenant = Tenant::create(['code' => 'POL', 'name' => 'Policy Tenant', 'status' => 'active']);
        $otherTenant = Tenant::create(['code' => 'OTH', 'name' => 'Other Tenant', 'status' => 'active']);
        $user = User::create(['name' => 'Policy User', 'email' => 'policy@example.test', 'password' => 'password']);
        $user->tenants()->attach($tenant->id);
        $role = Role::create(['name' => 'academic_operator', 'guard_name' => 'web']);
        $role->givePermissionTo(Permission::firstOrCreate(['name' => 'academic.view', 'guard_name' => 'web']));
        $user->assignRole($role);
        app(TenantContext::class)->set($tenant);
        $student = Student::create(['tenant_id' => $tenant->id, 'nim' => 'POL-001', 'entry_year' => 2026, 'status' => 'active']);
        $otherStudent = Student::create(['tenant_id' => $otherTenant->id, 'nim' => 'OTH-001', 'entry_year' => 2026, 'status' => 'active']);

        $this->assertTrue(Gate::forUser($user)->allows('viewAny', Student::class));
        $this->assertTrue(Gate::forUser($user)->allows('view', $student));
        $this->assertFalse(Gate::forUser($user)->allows('view', $otherStudent));
        $this->assertFalse(Gate::forUser($user)->allows('create', Student::class));
    }

    public function test_actor_specific_policies_respect_domain_roles(): void
    {
        $tenant = Tenant::create(['code' => 'ACT', 'name' => 'Actor Tenant', 'status' => 'active']);
        app(TenantContext::class)->set($tenant);
        $finance = $this->userWithRoleAndPermission('finance@example.test', 'finance', ['finance.view', 'finance.manage'], $tenant);
        $pmb = $this->userWithRoleAndPermission('pmb@example.test', 'pmb_officer', ['pmb.view', 'pmb.manage'], $tenant);
        $graduation = $this->userWithRoleAndPermission('graduation@example.test', 'graduation_officer', ['academic.view', 'academic.manage'], $tenant);
        $pddikti = $this->userWithRoleAndPermission('pddikti@example.test', 'admin', ['pddikti.view', 'pddikti.sync'], $tenant);
        $bill = new AcademicBill(['tenant_id' => $tenant->id, 'status' => 'unpaid']);
        $applicant = new Applicant(['tenant_id' => $tenant->id, 'status' => 'draft']);
        $graduationRecord = new Graduation(['tenant_id' => $tenant->id, 'status' => 'approved']);
        $syncLog = new PddiktiSyncLog(['tenant_id' => $tenant->id, 'status' => 'failed']);

        $this->assertTrue(Gate::forUser($finance)->allows('update', $bill));
        $this->assertFalse(Gate::forUser($finance)->allows('view', $applicant));
        $this->assertTrue(Gate::forUser($pmb)->allows('update', $applicant));
        $this->assertFalse(Gate::forUser($pmb)->allows('view', $bill));
        $this->assertTrue(Gate::forUser($graduation)->allows('view', $graduationRecord));
        $this->assertFalse(Gate::forUser($graduation)->allows('update', $graduationRecord));
        $this->assertTrue(Gate::forUser($pddikti)->allows('update', $syncLog));
    }

    private function userWithRoleAndPermission(string $email, string $roleName, array $permissions, Tenant $tenant): User
    {
        $user = User::create(['name' => $roleName, 'email' => $email, 'password' => 'password']);
        $user->tenants()->attach($tenant->id);
        $role = Role::create(['name' => $roleName, 'guard_name' => 'web']);
        foreach ($permissions as $permission) $role->givePermissionTo(Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']));
        $user->assignRole($role);
        return $user;
    }
}

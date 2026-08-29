<?php

namespace Tests\Feature\Foundation;

use App\Models\{Student, Tenant, User};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\{Permission, Role};
use Tests\TestCase;

class IdentityP0Test extends TestCase
{
    use RefreshDatabase;

    public function test_pddikti_api_route_is_registered_and_requires_sanctum(): void
    {
        $this->postJson('/api/v1/pddikti/sync', ['entity_type' => 'student', 'entity_id' => 1])->assertUnauthorized();
    }

    public function test_pddikti_sync_requires_permission_and_uses_active_tenant(): void
    {
        [$tenant, $admin] = $this->adminFixture();
        $student = Student::create(['tenant_id' => $tenant->id, 'nim' => 'P0-001', 'entry_year' => 2026, 'status' => 'active']);
        Sanctum::actingAs($admin, ['pddikti.sync']);
        Queue::fake();

        $this->postJson('/api/v1/pddikti/sync', ['entity_type' => 'student', 'entity_id' => $student->id], ['X-Tenant-ID' => (string) $tenant->id])
            ->assertAccepted()->assertJsonPath('queued', true);

        $this->postJson('/api/v1/pddikti/sync', ['entity_type' => 'student', 'entity_id' => $student->id], ['X-Tenant-ID' => '999999'])
            ->assertForbidden();
    }

    public function test_user_without_pddikti_permission_is_forbidden(): void
    {
        [$tenant, $user] = $this->adminFixture(false);
        Sanctum::actingAs($user);
        $this->postJson('/api/v1/pddikti/sync', ['entity_type' => 'student', 'entity_id' => 1], ['X-Tenant-ID' => (string) $tenant->id])
            ->assertForbidden();
    }

    private function adminFixture(bool $withPermission = true): array
    {
        $tenant = Tenant::create(['code' => 'P0', 'name' => 'P0 Tenant', 'status' => 'active']);
        $user = User::create(['name' => 'P0 Admin', 'email' => 'p0-'.uniqid().'@example.test', 'password' => 'password']);
        $user->tenants()->attach($tenant->id, ['is_active' => true]);
        $role = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        if ($withPermission) $role->givePermissionTo(Permission::firstOrCreate(['name' => 'pddikti.sync', 'guard_name' => 'web']));
        $user->assignRole($role);
        return [$tenant, $user];
    }
}

<?php

namespace Tests\Feature\Foundation;

use App\Actions\Tenants\TransitionTenantStatusAction;
use App\Enums\TenantStatus;
use App\Models\Course;
use App\Models\Tenant;
use App\Services\TenantContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class TenantFoundationTest extends TestCase
{
    use RefreshDatabase;

    public function test_context_can_be_required_and_temporarily_scoped(): void
    {
        $tenant = Tenant::create(['code' => 'TST', 'name' => 'Tenant Test', 'status' => 'active']);
        $context = app(TenantContext::class);

        $this->assertFalse($context->check());
        $this->expectException(\RuntimeException::class);
        $context->require();

        $context->run($tenant, function () use ($context, $tenant) {
            $this->assertTrue($context->check());
            $this->assertSame($tenant->id, $context->id());
        });
        $this->assertFalse($context->check());
    }

    public function test_tenant_scope_filters_domain_queries(): void
    {
        $first = Tenant::create(['code' => 'ONE', 'name' => 'One', 'status' => 'active']);
        $second = Tenant::create(['code' => 'TWO', 'name' => 'Two', 'status' => 'active']);
        Course::create(['tenant_id' => $first->id, 'code' => 'ONE-101', 'name' => 'First', 'credits' => 3]);
        Course::create(['tenant_id' => $second->id, 'code' => 'TWO-101', 'name' => 'Second', 'credits' => 3]);

        app(TenantContext::class)->set($first);
        $this->assertSame(['ONE-101'], Course::query()->pluck('code')->all());
        $this->assertSame(2, Course::query()->withoutGlobalScope('tenant')->count());
        app(TenantContext::class)->clear();
    }

    public function test_invalid_tenant_transition_is_rejected(): void
    {
        $tenant = Tenant::create(['code' => 'TRI', 'name' => 'Trial', 'status' => 'trial']);
        $this->expectException(ValidationException::class);
        app(TransitionTenantStatusAction::class)->execute($tenant, TenantStatus::Trial, 1);
    }
}

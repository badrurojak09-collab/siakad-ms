<?php

namespace App\Actions\Tenants;

use App\Models\{Tenant, User};
use App\Services\TenantContext;
use Illuminate\Validation\ValidationException;

class SwitchTenantAction
{
    public function execute(User $user, Tenant $tenant): Tenant
    {
        $allowed = $user->hasRole('platform_superadmin')
            || $user->tenants()->whereKey($tenant)->wherePivot('is_active', true)->exists();

        if (! $allowed) {
            throw ValidationException::withMessages(['tenant' => 'User tidak memiliki membership aktif pada tenant ini.']);
        }
        if (! $user->hasRole('platform_superadmin') && ! $tenant->isOperational()) {
            throw ValidationException::withMessages(['tenant' => 'Tenant tidak sedang operasional.']);
        }

        app(TenantContext::class)->set($tenant);
        activity('tenant')->causedBy($user)->performedOn($tenant)->log('tenant.switched');
        return $tenant;
    }
}

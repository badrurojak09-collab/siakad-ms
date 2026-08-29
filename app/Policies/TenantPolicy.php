<?php

namespace App\Policies;

use App\Models\Tenant;
use App\Models\User;

class TenantPolicy
{
    public function before(User $user): ?bool
    {
        return $user->hasRole('platform_superadmin') ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['superadmin', 'admin']) && $user->can('tenant.view_any');
    }

    public function view(User $user, Tenant $tenant): bool
    {
        return $user->hasAnyRole(['superadmin', 'admin']) && $user->tenants()->whereKey($tenant->getKey())->wherePivot('is_active', true)->exists();
    }

    public function create(User $user): bool
    {
        return $user->hasRole('platform_superadmin') && $user->can('tenant.create');
    }

    public function update(User $user, Tenant $tenant): bool
    {
        return $user->hasAnyRole(['superadmin', 'admin'])
            && $user->can('tenant.update')
            && $user->tenants()->whereKey($tenant->getKey())->wherePivot('is_active', true)->exists();
    }

    public function delete(User $user, Tenant $tenant): bool
    {
        return $user->hasRole('platform_superadmin') && $user->can('tenant.delete') && $tenant->status->value === 'inactive';
    }
}

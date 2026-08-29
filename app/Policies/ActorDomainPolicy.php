<?php

namespace App\Policies;

use App\Models\{Student, User};
use App\Services\TenantContext;
use Illuminate\Database\Eloquent\Model;

abstract class ActorDomainPolicy
{
    protected string $permissionPrefix = 'academic';
    protected array $roles = ['superadmin', 'admin', 'academic_operator'];
    protected bool $studentReadOnly = false;

    public function before(User $user): ?bool
    {
        return $user->hasAnyRole(['superadmin', 'platform_superadmin']) ? true : null;
    }

    public function viewAny(User $user): bool { return $this->actorCan($user, 'view') || $this->studentCanView($user); }
    public function view(User $user, Model $record): bool { return $this->sameTenant($user, $record) && ($this->actorCan($user, 'view') || ($this->studentCanView($user) && $this->studentOwns($user, $record))); }
    public function create(User $user): bool { return $this->actorCan($user, 'manage'); }
    public function update(User $user, Model $record): bool { return $this->sameTenant($user, $record) && $this->actorCan($user, 'manage') && $this->mutable($record); }
    public function delete(User $user, Model $record): bool { return $this->sameTenant($user, $record) && $this->actorCan($user, 'manage') && $this->deletable($record); }

    protected function actorCan(User $user, string $operation): bool
    {
        return $user->hasAnyRole($this->roles) && $user->can($this->permissionPrefix.'.'.$operation);
    }

    protected function studentCanView(User $user): bool
    {
        return $this->studentReadOnly && $user->hasRole('student') && $user->can($this->permissionPrefix.'.view');
    }

    protected function studentOwns(User $user, Model $record): bool
    {
        $studentId = $user->student?->getKey();
        if (! $studentId) return false;
        if ($record instanceof Student) return (int) $record->getKey() === (int) $studentId;
        return (int) $record->getAttribute('student_id') === (int) $studentId;
    }

    protected function sameTenant(User $user, Model $record): bool
    {
        $tenantId = app(TenantContext::class)->id();
        return filled($tenantId) && (int) $record->getAttribute('tenant_id') === (int) $tenantId && $user->tenants()->whereKey($tenantId)->exists();
    }

    protected function mutable(Model $record): bool
    {
        return ! in_array($record->getAttribute('status'), ['final', 'published', 'paid', 'closed', 'completed', 'issued', 'approved', 'cancelled'], true);
    }

    protected function deletable(Model $record): bool
    {
        return in_array($record->getAttribute('status'), [null, 'draft', 'planned', 'unpaid', 'failed', 'cancelled'], true);
    }
}

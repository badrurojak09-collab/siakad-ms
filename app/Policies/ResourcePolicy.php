<?php

namespace App\Policies;

use App\Models\User;
use App\Services\TenantContext;
use Illuminate\Database\Eloquent\Model;

class ResourcePolicy
{
    public function before(User $user): ?bool
    {
        return $user->hasAnyRole(['superadmin', 'platform_superadmin']) ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return collect(['academic.view', 'krs.view', 'attendance.view', 'grading.view', 'finance.view', 'pmb.view', 'report.view', 'pddikti.view'])->contains(fn (string $permission): bool => $user->can($permission));
    }

    public function view(User $user, Model $record): bool
    {
        return $this->sameTenant($user, $record) && $this->allowed($user, $record::class, 'view');
    }

    public function create(User $user): bool
    {
        return collect(['academic.manage', 'krs.manage', 'attendance.manage', 'grading.manage', 'finance.manage', 'pmb.manage', 'report.generate', 'pddikti.sync'])->contains(fn (string $permission): bool => $user->can($permission));
    }

    public function update(User $user, Model $record): bool
    {
        return $this->sameTenant($user, $record) && $this->allowed($user, $record::class, 'manage');
    }

    public function delete(User $user, Model $record): bool
    {
        return $this->sameTenant($user, $record) && $this->allowed($user, $record::class, 'manage');
    }

    private function sameTenant(User $user, Model $record): bool
    {
        $tenantId = app(TenantContext::class)->id();
        return filled($tenantId) && (int) $record->getAttribute('tenant_id') === (int) $tenantId && $user->tenants()->whereKey($tenantId)->exists();
    }

    private function allowed(User $user, string $modelClass, string $operation): bool
    {
        $permission = $this->permissionPrefix($modelClass).'.'.$operation;
        if ($user->can($permission)) return true;
        return $operation === 'view' && $user->can($this->permissionPrefix($modelClass).'.view');
    }

    private function permissionPrefix(string $modelClass): string
    {
        $name = class_basename($modelClass);
        return match (true) {
            str_contains($name, 'Payment'), str_contains($name, 'Bill'), $name === 'FeeType' => 'finance',
            str_contains($name, 'Applicant'), str_contains($name, 'Admission') => 'pmb',
            str_contains($name, 'Attendance') => 'attendance',
            str_contains($name, 'Grade'), $name === 'Assessment', $name === 'GradeSubmission' => 'grading',
            str_contains($name, 'Krs') => 'krs',
            str_contains($name, 'Report'), $name === 'AcademicTranscript' => 'report',
            str_contains($name, 'Pddikti') => 'pddikti',
            default => 'academic',
        };
    }
}

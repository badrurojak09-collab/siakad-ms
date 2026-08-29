<?php
namespace App\Policies;
class ReportingPolicy extends ActorDomainPolicy
{
    protected string $permissionPrefix = 'report';
    protected array $roles = ['admin', 'academic_operator', 'finance'];
    protected bool $studentReadOnly = true;
    protected function actorCan(\App\Models\User $user, string $operation): bool
    {
        $permission = $operation === 'manage' ? 'report.generate' : 'report.view';
        return $user->hasAnyRole($this->roles) && $user->can($permission);
    }
}

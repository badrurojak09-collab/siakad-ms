<?php
namespace App\Policies;
class PddiktiPolicy extends ActorDomainPolicy
{
    public function sync(\App\Models\User $user): bool
    {
        return $this->actorCan($user, 'manage');
    }

    protected string $permissionPrefix = 'pddikti';
    protected array $roles = ['admin'];
    protected function actorCan(\App\Models\User $user, string $operation): bool
    {
        $permission = $operation === 'manage' ? 'pddikti.sync' : 'pddikti.view';
        return $user->hasAnyRole($this->roles) && $user->can($permission);
    }
}

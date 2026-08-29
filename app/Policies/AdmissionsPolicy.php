<?php
namespace App\Policies;
class AdmissionsPolicy extends ActorDomainPolicy
{
    protected string $permissionPrefix = 'pmb';
    protected array $roles = ['admin', 'academic_operator', 'pmb_officer'];
}

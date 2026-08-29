<?php
namespace App\Policies;
class KrsPolicy extends ActorDomainPolicy
{
    protected string $permissionPrefix = 'krs';
    protected array $roles = ['admin', 'academic_operator', 'lecturer'];
    protected bool $studentReadOnly = true;
}

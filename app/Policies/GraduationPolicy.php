<?php
namespace App\Policies;
class GraduationPolicy extends ActorDomainPolicy
{
    protected string $permissionPrefix = 'academic';
    protected array $roles = ['admin', 'academic_operator', 'graduation_officer'];
    protected bool $studentReadOnly = true;
}

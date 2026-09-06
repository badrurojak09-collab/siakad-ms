<?php
namespace App\Policies;

class AcademicPolicy extends ActorDomainPolicy
{
    protected string $permissionPrefix = 'academic';
    protected array $roles = ['admin', 'academic_operator', 'lecturer'];
}

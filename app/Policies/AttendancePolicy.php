<?php
namespace App\Policies;
class AttendancePolicy extends ActorDomainPolicy
{
    protected string $permissionPrefix = 'attendance';
    protected array $roles = ['admin', 'academic_operator', 'lecturer'];
    protected bool $studentReadOnly = true;
}

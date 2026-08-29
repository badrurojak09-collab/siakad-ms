<?php
namespace App\Policies;
class GradingPolicy extends ActorDomainPolicy
{
    protected string $permissionPrefix = 'grading';
    protected array $roles = ['admin', 'academic_operator', 'lecturer'];
    protected bool $studentReadOnly = true;
}

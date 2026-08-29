<?php
namespace App\Policies;
class ThesisPolicy extends ActorDomainPolicy
{
    protected string $permissionPrefix = 'academic';
    protected array $roles = ['admin', 'academic_operator', 'lecturer'];
    protected bool $studentReadOnly = true;
}

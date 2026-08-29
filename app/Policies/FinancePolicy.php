<?php
namespace App\Policies;
class FinancePolicy extends ActorDomainPolicy
{
    protected string $permissionPrefix = 'finance';
    protected array $roles = ['admin', 'finance'];
    protected bool $studentReadOnly = true;
}

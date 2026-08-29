<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class AdministrationPolicy extends ActorDomainPolicy
{
    protected string $permissionPrefix = 'academic';
    protected array $roles = ['admin', 'academic_operator'];
    protected bool $studentReadOnly = true;

    public function view(User $user, Model $record): bool
    {
        if (parent::view($user, $record)) return true;
        return $user->hasRole('student') && $this->sameTenant($user, $record) && (int) $record->getAttribute('student_id') === (int) optional($user->student)->id;
    }

    public function approve(User $user, Model $record): bool
    {
        return parent::view($user, $record) && $record->getAttribute('status') === 'pending';
    }

    public function submit(User $user, Model $record): bool
    {
        return $this->sameTenant($user, $record) && $record->getAttribute('status') === 'draft' && $user->hasRole('student') && (int) $record->getAttribute('student_id') === (int) optional($user->student)->id;
    }
}

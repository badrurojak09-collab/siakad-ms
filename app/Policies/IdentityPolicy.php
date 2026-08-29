<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class IdentityPolicy
{
    protected array $roles = ['superadmin', 'platform_superadmin', 'admin'];

    public function viewAny(User $user): bool
    {
        return $this->canView($user);
    }

    public function view(User $user, Model $record): bool
    {
        return $this->canView($user);
    }

    public function create(User $user): bool
    {
        return $this->canManage($user);
    }

    public function update(User $user, Model $record): bool
    {
        if ($record instanceof User && $record->is($user) && ! $user->hasAnyRole(['superadmin', 'platform_superadmin'])) {
            return false;
        }

        return $this->canManage($user);
    }

    public function delete(User $user, Model $record): bool
    {
        if ($record instanceof User && $record->is($user)) {
            return false;
        }

        return $this->canManage($user);
    }

    public function restore(User $user, Model $record): bool { return $this->canManage($user); }
    public function forceDelete(User $user, Model $record): bool { return false; }

    protected function canView(User $user): bool
    {
        return $user->hasAnyRole($this->roles) && ($user->can('identity.view') || $user->hasAnyRole(['superadmin', 'platform_superadmin']));
    }

    protected function canManage(User $user): bool
    {
        return $user->hasAnyRole($this->roles) && ($user->can('identity.manage') || $user->hasAnyRole(['superadmin', 'platform_superadmin']));
    }
}

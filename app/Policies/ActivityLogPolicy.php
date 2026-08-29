<?php

namespace App\Policies;

use App\Models\User;
use Spatie\Activitylog\Models\Activity;

class ActivityLogPolicy
{
    public function before(User $user): ?bool
    {
        return $user->hasAnyRole(['platform_superadmin', 'superadmin']) ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin') && $user->can('audit.view');
    }

    public function view(User $user, Activity $activity): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool { return false; }
    public function update(User $user, Activity $activity): bool { return false; }
    public function delete(User $user, Activity $activity): bool { return false; }
    public function restore(User $user, Activity $activity): bool { return false; }
    public function forceDelete(User $user, Activity $activity): bool { return false; }
}

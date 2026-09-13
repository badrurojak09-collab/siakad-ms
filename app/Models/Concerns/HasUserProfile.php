<?php

namespace App\Models\Concerns;

use App\Models\UserProfile;
use Illuminate\Database\Eloquent\Relations\HasMany;
// use Illuminate\Database\Eloquent\Model;

trait HasUserProfile
{
    /**
     * @return HasMany<UserProfile, $this>
     */
    public function userProfiles(): HasMany
    {
        return $this->hasMany(UserProfile::class);
    }

    public function userProfileFor(?int $tenantId = null): ?UserProfile
    {
        return $this->userProfiles()
            ->when($tenantId, fn($query) => $query->where('tenant_id', $tenantId))
            ->first();
    }
}

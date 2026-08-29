<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasRoles, Notifiable;

    protected $fillable = ['name', 'email', 'password'];

    public function tenants(): BelongsToMany { return $this->belongsToMany(Tenant::class)->withTimestamps(); }
    public function student(): HasOne { return $this->hasOne(Student::class); }

    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() !== 'admin') return false;
        if (! $this->exists || ! $this->hasAnyRole(['superadmin', 'platform_superadmin', 'admin', 'academic_operator', 'lecturer', 'student', 'finance', 'pmb_officer', 'graduation_officer'])) return false;
        return $this->tenants()->wherePivot('is_active', true)->get()->contains(fn (Tenant $tenant): bool => $tenant->isOperational());
    }

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}

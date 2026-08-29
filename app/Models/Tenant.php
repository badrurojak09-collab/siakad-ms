<?php
namespace App\Models;
use App\Enums\TenantStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
class Tenant extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
    protected $casts = ['status' => TenantStatus::class, 'config' => 'array', 'metadata' => 'array', 'subscription_expiry' => 'date'];
    public function users(): BelongsToMany { return $this->belongsToMany(User::class)->withTimestamps(); }
    public function isOperational(): bool { return $this->status?->operational() ?? false; }
}

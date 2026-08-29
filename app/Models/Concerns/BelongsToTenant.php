<?php
namespace App\Models\Concerns;
use App\Services\TenantContext;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Tenant;
trait BelongsToTenant
{
    public static function bootBelongsToTenant(): void
    {
        static::creating(function ($model) {
            if (app(TenantContext::class)->check() && blank($model->tenant_id)) $model->tenant_id = app(TenantContext::class)->id();
            if (blank($model->tenant_id) && ! app()->runningInConsole()) throw new \RuntimeException('Tenant context wajib tersedia.');
        });
        static::addGlobalScope('tenant', function (Builder $builder) {
            $context = app(TenantContext::class);
            if ($context->check()) {
                $builder->where($builder->getModel()->qualifyColumn('tenant_id'), $context->id());
            } elseif (! app()->runningInConsole()) {
                $builder->whereRaw('1 = 0');
            }
        });
    }
    public function tenant(): BelongsTo { return $this->belongsTo(Tenant::class); }
    public function scopeForTenant(Builder $query, int $tenantId): Builder { return $query->withoutGlobalScope('tenant')->where($this->qualifyColumn('tenant_id'), $tenantId); }
}

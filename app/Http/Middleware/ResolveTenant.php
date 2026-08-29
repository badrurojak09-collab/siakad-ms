<?php
namespace App\Http\Middleware;
use App\Models\Tenant;
use App\Services\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
class ResolveTenant
{
    public function __construct(private TenantContext $context) {}
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (! $user) return $next($request);
        $candidate = $request->header('X-Tenant-ID') ?: $request->session()->get('tenant_id');
        if (! $candidate && $request->route('tenant')) $candidate = $request->route('tenant');
        if (! $candidate) {
            $candidate = $user->tenants()->wherePivot('is_active', true)->value('tenants.id');
        }
        if (! $candidate) return $next($request); // platform user may operate without tenant context.
        $tenant = $user->hasRole('platform_superadmin')
            ? Tenant::query()->findOrFail($candidate)
            : $user->tenants()->whereKey($candidate)->wherePivot('is_active', true)->first();
        abort_unless($tenant, 403, 'Tenant tidak diizinkan.');
        abort_unless($tenant->isOperational() || $user->hasRole('platform_superadmin'), 423, 'Tenant tidak operasional.');
        $this->context->set($tenant);
        app()->instance(Tenant::class, $tenant);
        try {
            return $next($request);
        } finally {
            $this->context->clear();
        }
    }
}

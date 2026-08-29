<?php

namespace App\Http\Middleware;

use App\Services\TenantContext;
use Closure;
use Illuminate\Http\Request;

class EnsureTenantOperational
{
    public function handle(Request $request, Closure $next)
    {
        // Halaman login dan route guest lain belum memiliki user maupun tenant context.
        // Tenant wajib diperiksa setelah autentikasi berhasil.
        if (! $request->user()) {
            return $next($request);
        }

        $tenant = app(TenantContext::class)->get();

        abort_unless($tenant && $tenant->isOperational(), 423, 'Tenant belum operasional.');

        return $next($request);
    }
}

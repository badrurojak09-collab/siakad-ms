<?php

namespace App\Services;

use App\Models\Tenant;
use Closure;
use RuntimeException;

class TenantContext
{
    private ?Tenant $tenant = null;

    public function set(Tenant $tenant): void
    {
        if ($this->tenant && $this->tenant->isNot($tenant)) {
            throw new RuntimeException('Tenant context sudah ditetapkan pada request ini.');
        }
        $this->tenant = $tenant;
    }

    public function run(Tenant $tenant, Closure $callback): mixed
    {
        $previous = $this->tenant;
        $this->tenant = $tenant;
        try {
            return $callback($tenant);
        } finally {
            $this->tenant = $previous;
        }
    }

    public function clear(): void { $this->tenant = null; }
    public function get(): ?Tenant { return $this->tenant; }
    public function id(): int { if (! $this->tenant) throw new RuntimeException('Tenant context belum tersedia.'); return (int) $this->tenant->getKey(); }
    public function check(): bool { return $this->tenant !== null; }
    public function require(): Tenant { return $this->tenant ?? throw new RuntimeException('Tenant context wajib tersedia.'); }
}

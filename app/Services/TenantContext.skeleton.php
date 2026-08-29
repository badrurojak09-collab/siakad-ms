<?php
namespace App\Services;
use App\Models\Tenant;
use RuntimeException;
class TenantContext
{
    private ?Tenant $tenant = null;
    public function set(Tenant $tenant): void { if ($this->tenant && $this->tenant->isNot($tenant)) throw new RuntimeException('Tenant context sudah ditetapkan.'); $this->tenant = $tenant; }
    public function clear(): void { $this->tenant = null; }
    public function get(): ?Tenant { return $this->tenant; }
    public function id(): int { if (! $this->tenant) throw new RuntimeException('Tenant context belum tersedia.'); return $this->tenant->getKey(); }
    public function check(): bool { return $this->tenant !== null; }
}

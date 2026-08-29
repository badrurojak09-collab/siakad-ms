<?php

namespace App\Actions\Tenants;

use App\Enums\TenantStatus;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TransitionTenantStatusAction
{
    private const TRANSITIONS = [
        'trial' => ['active', 'suspended', 'inactive'],
        'active' => ['suspended', 'inactive'],
        'suspended' => ['active', 'inactive'],
        'inactive' => ['trial'],
    ];

    public function execute(Tenant $tenant, TenantStatus $target, int $actorId, ?string $reason = null): Tenant
    {
        $from = $tenant->status instanceof TenantStatus ? $tenant->status->value : (string) $tenant->status;
        if ($from === $target->value) {
            throw ValidationException::withMessages(['status' => 'Status tenant tidak berubah.']);
        }
        if (! in_array($target->value, self::TRANSITIONS[$from] ?? [], true)) {
            throw ValidationException::withMessages(['status' => "Transisi {$from} ke {$target->value} tidak diizinkan."]);
        }
        if ($target === TenantStatus::Active && blank($tenant->name)) {
            throw ValidationException::withMessages(['name' => 'Tenant aktif wajib memiliki nama institusi.']);
        }

        return DB::transaction(function () use ($tenant, $target, $actorId, $reason, $from) {
            $tenant->update(['status' => $target]);
            activity('tenant')
                ->causedBy($actorId)
                ->performedOn($tenant)
                ->withProperties(['from' => $from, 'to' => $target->value, 'reason' => $reason])
                ->log('tenant.status_changed');
            return $tenant->refresh();
        });
    }
}

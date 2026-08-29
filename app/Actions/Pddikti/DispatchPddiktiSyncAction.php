<?php

namespace App\Actions\Pddikti;

use App\Jobs\SyncToPddikti;
use App\Models\PddiktiSyncLog;
use Illuminate\Support\Str;

class DispatchPddiktiSyncAction
{
    public function execute(string $entityType, string $entityId, string $operation = 'upsert', ?int $tenantId = null): PddiktiSyncLog
    {
        $key = hash('sha256', implode('|', [$tenantId, $entityType, $entityId, $operation]));
        $log = PddiktiSyncLog::firstOrCreate(
            ['tenant_id' => $tenantId, 'idempotency_key' => $key],
            ['entity_type' => $entityType, 'entity_id' => $entityId, 'operation' => $operation, 'status' => 'queued', 'retry_count' => 0, 'metadata' => ['request_id' => (string) Str::uuid()]],
        );
        if ($log->wasRecentlyCreated || in_array($log->status, ['failed', 'retry'], true)) {
            SyncToPddikti::dispatch($entityType, $entityId, $operation, $tenantId)->afterCommit();
        }
        return $log->refresh();
    }
}

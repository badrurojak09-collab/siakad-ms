<?php

namespace App\Actions\Pddikti;

use App\Jobs\SyncToPddikti;
use App\Models\PddiktiSyncLog;

class RetryPddiktiSyncAction
{
    public function execute(PddiktiSyncLog $log): void
    {
        $log->update(['status' => 'retry', 'retry_count' => $log->retry_count + 1]);
        SyncToPddikti::dispatch($log->entity_type, (string) $log->entity_id, $log->operation, $log->tenant_id)->afterCommit();
    }
}

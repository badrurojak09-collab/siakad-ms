<?php

namespace App\Listeners;

use App\Events\PddiktiSyncRequested;
use App\Jobs\SyncToPddikti;

class QueuePddiktiSync
{
    public function handle(PddiktiSyncRequested $event): void
    {
        SyncToPddikti::dispatch($event->entityType, $event->entityId);
    }
}

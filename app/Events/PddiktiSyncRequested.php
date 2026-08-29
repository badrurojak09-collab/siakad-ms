<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PddiktiSyncRequested
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly string $entityType,
        public readonly string $entityId,
    ) {}
}

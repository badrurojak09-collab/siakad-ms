<?php

namespace App\Services\Pddikti;

class NullPddiktiClient implements PddiktiClient
{
    public function upsert(string $entityType, string $entityId, string $operation, array $payload, string $idempotencyKey): array
    {
        return ['response_code' => 200, 'response_message' => 'Accepted by local PDDikti adapter.', 'remote_id' => $entityType.'-'.$entityId];
    }
}

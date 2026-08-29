<?php

namespace App\Services\Pddikti;

interface PddiktiClient
{
    /** @return array{response_code:int,response_message:string,remote_id?:string} */
    public function upsert(string $entityType, string $entityId, string $operation, array $payload, string $idempotencyKey): array;
}

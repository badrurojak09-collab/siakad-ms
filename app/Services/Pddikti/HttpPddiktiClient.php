<?php

namespace App\Services\Pddikti;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class HttpPddiktiClient implements PddiktiClient
{
    public function upsert(string $entityType, string $entityId, string $operation, array $payload, string $idempotencyKey): array
    {
        $response = Http::baseUrl((string) config('services.pddikti.base_url'))
            ->withToken((string) config('services.pddikti.token'))
            ->acceptJson()->asJson()->timeout((int) config('services.pddikti.timeout', 30))
            ->retry(2, 250)->post('/sync', compact('entityType', 'entityId', 'operation', 'payload', 'idempotencyKey'));

        if ($response->failed()) {
            throw new RuntimeException($response->body(), $response->status());
        }
        return ['response_code' => $response->status(), 'response_message' => (string) ($response->json('message') ?: 'Accepted.'), 'remote_id' => $response->json('data.id')];
    }
}

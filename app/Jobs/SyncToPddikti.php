<?php

namespace App\Jobs;

use App\Models\{AcademicTranscript, PddiktiSyncLog, Student};
use App\Services\Pddikti\{PddiktiClient, PddiktiPayloadMapper};
use Illuminate\Bus\{Batchable, Queueable};
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\Facades\Log;
use Throwable;

class SyncToPddikti implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 5;

    public function __construct(public readonly string $entityType, public readonly string $entityId, public readonly string $operation = 'upsert', public readonly ?int $tenantId = null) {}

    public function backoff(): array { return [60, 300, 900, 1800]; }

    public function handle(PddiktiClient $client, PddiktiPayloadMapper $mapper): void
    {
        $key = hash('sha256', implode('|', [$this->tenantId, $this->entityType, $this->entityId, $this->operation]));
        $log = PddiktiSyncLog::firstOrCreate(
            ['tenant_id' => $this->tenantId, 'idempotency_key' => $key],
            ['entity_type' => $this->entityType, 'entity_id' => $this->entityId, 'operation' => $this->operation, 'status' => 'pending', 'retry_count' => 0],
        );
        if ($log->status === 'synced') return;
        $entity = match ($this->entityType) {
            'student' => Student::where('tenant_id', $this->tenantId)->findOrFail($this->entityId),
            'transcript', 'academic_transcript' => AcademicTranscript::where('tenant_id', $this->tenantId)->findOrFail($this->entityId),
            default => throw new \InvalidArgumentException('Entity PDDikti belum memiliki mapper: '.$this->entityType),
        };
        $payload = $entity instanceof Student ? $mapper->student($entity) : $mapper->transcript($entity->load('items'));
        $log->update(['status' => 'processing', 'payload' => $payload, 'adapter' => get_class($client), 'last_attempt_at' => now(), 'retry_count' => max($log->retry_count, $this->attempts() - 1)]);
        try {
            $result = $client->upsert($this->entityType, $this->entityId, $this->operation, $payload, $key);
            $log->update(['status' => 'synced', 'response_code' => $result['response_code'], 'response_message' => $result['response_message'], 'synced_at' => now(), 'metadata' => array_merge($log->metadata ?: [], ['remote_id' => $result['remote_id'] ?? null])]);
        } catch (Throwable $exception) {
            $log->update(['status' => 'failed', 'response_message' => $exception->getMessage(), 'error_class' => $exception::class]);
            Log::error('PDDikti synchronization failed', ['log_id' => $log->id, 'exception' => $exception]);
            throw $exception;
        }
    }
}

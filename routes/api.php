<?php

use App\Actions\Pddikti\DispatchPddiktiSyncAction;
use App\Models\{AcademicTranscript, PddiktiSyncLog, Student};
use App\Services\TenantContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;

Route::get('/v1/health', fn () => ['status' => 'ok', 'application' => config('app.name')]);

Route::middleware(['auth:sanctum', 'tenant', 'tenant.operational', 'throttle:10,1'])
    ->post('/v1/pddikti/sync', function (Request $request, DispatchPddiktiSyncAction $dispatch) {
        Gate::authorize('sync', PddiktiSyncLog::class);

        $data = $request->validate([
            'entity_type' => ['required', 'string', Rule::in(['student', 'transcript', 'academic_transcript'])],
            'entity_id' => ['required', 'integer', 'min:1'],
            'operation' => ['sometimes', 'string', Rule::in(['upsert'])],
        ]);

        $tenantId = app(TenantContext::class)->id();
        $entity = match ($data['entity_type']) {
            'student' => Student::query()->whereKey($data['entity_id'])->firstOrFail(),
            'transcript', 'academic_transcript' => AcademicTranscript::query()->whereKey($data['entity_id'])->firstOrFail(),
        };

        if ($entity instanceof AcademicTranscript && $entity->status !== 'final') {
            return response()->json(['message' => 'Hanya transkrip final yang dapat disinkronkan.'], 422);
        }

        $log = $dispatch->execute($data['entity_type'], (string) $entity->getKey(), $data['operation'] ?? 'upsert', $tenantId);

        return response()->json([
            'queued' => true,
            'id' => $log->getKey(),
            'status' => $log->status,
            'idempotency_key' => $log->idempotency_key,
        ], 202);
    });

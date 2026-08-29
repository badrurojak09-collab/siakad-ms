<?php

namespace App\Actions\Pddikti;

use App\Models\PddiktiSyncLog;
use Illuminate\Support\Facades\DB;

class GetPddiktiSyncSummaryAction
{
    public function execute(?int $tenantId = null): array
    {
        $query = PddiktiSyncLog::query();
        if ($tenantId !== null) $query->where('tenant_id', $tenantId);
        $byStatus = $query->select('status', DB::raw('count(*) as total'))->groupBy('status')->pluck('total', 'status')->all();
        return ['total' => array_sum($byStatus), 'by_status' => $byStatus, 'failed' => (int) ($byStatus['failed'] ?? 0), 'synced' => (int) ($byStatus['synced'] ?? 0), 'pending' => (int) (($byStatus['queued'] ?? 0) + ($byStatus['pending'] ?? 0) + ($byStatus['processing'] ?? 0) + ($byStatus['retry'] ?? 0))];
    }
}

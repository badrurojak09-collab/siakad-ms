<?php

namespace App\Actions\Academic;

use App\Models\Semester;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class ActivateSemesterAction
{
    public function execute(Semester $semester): Semester
    {
        if (! app(\App\Services\TenantContext::class)->check()) {
            throw new RuntimeException('Tenant context wajib tersedia.');
        }

        return DB::transaction(function () use ($semester) {
            Semester::query()
                ->where('tenant_id', $semester->tenant_id)
                ->where($semester->getKeyName(), '<>', $semester->getKey())
                ->update(['is_active' => false]);

            $semester->update(['is_active' => true]);

            activity('academic_period')
                ->performedOn($semester)
                ->withProperties(['semester_id' => $semester->getKey()])
                ->log('semester.activated');

            return $semester->refresh();
        });
    }
}

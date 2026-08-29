<?php
namespace App\Actions\Tenants;
use App\Enums\TenantStatus;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
class CreateTenantAction
{
    public function execute(array $data, User $actor): Tenant
    {
        abort_unless($actor->hasRole('platform_superadmin'), 403);
        $code = Str::upper(trim($data['code']));
        $domain = isset($data['domain']) && $data['domain'] !== '' ? Str::lower(trim(parse_url($data['domain'], PHP_URL_HOST) ?: $data['domain'])) : null;
        return DB::transaction(function () use ($data, $actor, $code, $domain) {
            $tenant = Tenant::create([
                'name' => trim($data['name']), 'code' => $code, 'domain' => $domain,
                'status' => $data['status'] ?? TenantStatus::Trial,
                'config' => $data['config'] ?? ['timezone' => 'Asia/Jakarta', 'grade_system' => '4.0'],
                'subscription_plan' => $data['subscription_plan'] ?? 'trial',
                'max_students' => $data['max_students'] ?? 0, 'max_lecturers' => $data['max_lecturers'] ?? 0,
                'created_by' => $actor->getKey(),
            ]);
            $tenant->users()->syncWithoutDetaching([$actor->getKey() => ['is_active' => true, 'joined_at' => now()]]);
            activity('tenant')->performedOn($tenant)->causedBy($actor)->withProperties(['code' => $tenant->code])->log('tenant.created');
            return $tenant;
        });
    }
}

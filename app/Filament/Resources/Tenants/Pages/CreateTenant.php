<?php

namespace App\Filament\Resources\Tenants\Pages;

use App\Actions\Tenants\CreateTenantAction;
use App\Filament\Resources\Tenants\TenantResource;
use App\Models\Tenant;
use Filament\Resources\Pages\CreateRecord;

class CreateTenant extends CreateRecord
{
    protected static string $resource = TenantResource::class;

    protected function handleRecordCreation(array $data): Tenant
    {
        return app(CreateTenantAction::class)->execute($data, auth()->user());
    }
}

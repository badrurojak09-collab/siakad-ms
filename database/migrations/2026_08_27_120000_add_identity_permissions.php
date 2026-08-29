<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    public function up(): void
    {
        $view = Permission::firstOrCreate(['name' => 'identity.view', 'guard_name' => 'web']);
        $manage = Permission::firstOrCreate(['name' => 'identity.manage', 'guard_name' => 'web']);

        foreach (['superadmin', 'platform_superadmin', 'admin'] as $roleName) {
            $role = Role::where('name', $roleName)->where('guard_name', 'web')->first();
            if ($role) {
                $role->givePermissionTo([$view, $manage]);
            }
        }
    }

    public function down(): void
    {
        Permission::whereIn('name', ['identity.view', 'identity.manage'])->where('guard_name', 'web')->delete();
    }
};

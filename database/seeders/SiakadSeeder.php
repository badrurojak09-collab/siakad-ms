<?php

namespace Database\Seeders;

use App\Models\Tenant;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SiakadSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::create([
            'code' => 'DEMO',
            'name' => 'Institusi Demo',
            'status' => 'active',
            'config' => ['timezone' => 'Asia/Jakarta', 'grade_system' => '4.0'],
        ]);

        $permissions = collect([
            'tenant.view_any', 'tenant.view', 'tenant.create', 'tenant.update', 'tenant.delete',
            'academic.view', 'academic.manage', 'krs.view', 'krs.manage', 'krs.approve',
            'attendance.view', 'attendance.manage', 'grading.view', 'grading.manage', 'grading.publish',
            'finance.view', 'finance.manage', 'pmb.view', 'pmb.manage', 'report.view', 'report.generate',
            'pddikti.view', 'pddikti.sync',
        ])->map(fn (string $name) => Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']));

        $rolePermissions = [
            'superadmin' => $permissions->pluck('name')->all(),
            'admin' => $permissions->reject(fn ($permission) => str_starts_with($permission->name, 'pddikti.'))->pluck('name')->all(),
            'academic_operator' => ['academic.view', 'academic.manage', 'krs.view', 'krs.manage', 'attendance.view', 'grading.view', 'pmb.view', 'pmb.manage', 'report.view', 'report.generate'],
            'lecturer' => ['academic.view', 'krs.view', 'krs.approve', 'attendance.view', 'attendance.manage', 'grading.view', 'grading.manage'],
            'student' => ['academic.view', 'krs.view', 'krs.manage', 'attendance.view', 'grading.view', 'pmb.view'],
            'finance' => ['finance.view', 'finance.manage', 'report.view', 'report.generate'],
            'pmb_officer' => ['pmb.view', 'pmb.manage'],
            'graduation_officer' => ['academic.view', 'academic.manage', 'report.view', 'report.generate'],
        ];
        foreach ($rolePermissions as $roleName => $permissionNames) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            $role->syncPermissions($permissionNames);
        }
    }
}

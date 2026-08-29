<?php

namespace Database\Seeders;

use App\Models\{Lecturer, Student, Tenant, User};
use Illuminate\Database\Seeder;
// use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DemoAccountsSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::where('code', 'DEMO')->firstOrFail();
        $password = env('DEMO_PASSWORD', 'ChangeMe-Staging-2026!');

        $accounts = [
            ['role' => 'superadmin', 'email' => 'superadmin@demo.test', 'name' => 'Super Admin Demo'],
            ['role' => 'admin', 'email' => 'admin@demo.test', 'name' => 'Admin Demo'],
            ['role' => 'academic_operator', 'email' => 'akademik@demo.test', 'name' => 'Operator Akademik Demo'],
            ['role' => 'lecturer', 'email' => 'dosen@demo.test', 'name' => 'Dosen Demo'],
            ['role' => 'student', 'email' => 'mahasiswa@demo.test', 'name' => 'Mahasiswa Demo'],
            ['role' => 'finance', 'email' => 'keuangan@demo.test', 'name' => 'Finance Demo'],
            ['role' => 'pmb_officer', 'email' => 'pmb@demo.test', 'name' => 'Petugas PMB Demo'],
            ['role' => 'graduation_officer', 'email' => 'kelulusan@demo.test', 'name' => 'Petugas Kelulusan Demo'],
        ];

        foreach ($accounts as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                ['name' => $data['name'], 'password' => $password]
            );

            $user->tenants()->syncWithoutDetaching([$tenant->id]);
            $role = Role::where('name', $data['role'])->where('guard_name', 'web')->firstOrFail();
            $user->syncRoles([$role]);
        }

        $studentUser = User::where('email', 'mahasiswa@demo.test')->firstOrFail();
        Student::updateOrCreate(
            ['nim' => 'DEMO-001', 'tenant_id' => $tenant->id],
            [
                'user_id' => $studentUser->id,
                'entry_year' => now()->year,
                'status' => 'active',
            ]
        );

        $lecturerUser = User::where('email', 'dosen@demo.test')->firstOrFail();
        Lecturer::updateOrCreate(
            ['user_id' => $lecturerUser->id, 'tenant_id' => $tenant->id],
            ['nidn' => 'DEMO-DOSEN-001']
        );
    }
}

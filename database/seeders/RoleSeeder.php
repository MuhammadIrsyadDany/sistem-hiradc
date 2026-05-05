<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]
            ->forgetCachedPermissions();

        $permissions = [
            // HIRADC
            'hiradc.view',
            'hiradc.create',
            'hiradc.validate_v1',
            'hiradc.validate_v2',
            // Program Kerja
            'program_kerja.view',
            'program_kerja.create',
            'program_kerja.upload_bukti',
            'program_kerja.close',
            // Live Audit
            'live_audit.view',
            'live_audit.create',
            'live_audit.validate_v1',
            'live_audit.validate_v2',
            // Temuan
            'temuan.view',
            'temuan.create',
            'temuan.validate_v1',
            'temuan.validate_v2',
            'temuan.close',
            // Master Data
            'checklist_items.manage',
            'users.manage',
            // Dashboard
            'dashboard.view',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Role Admin — akses penuh
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());

        // Role Validator 1 — Asisten Manajer K3
        $validator1 = Role::firstOrCreate(['name' => 'validator_1']);
        $validator1->givePermissionTo([
            'dashboard.view',
            'hiradc.view',
            'hiradc.validate_v1',
            'program_kerja.view',
            'live_audit.view',
            'live_audit.validate_v1',
            'temuan.view',
            'temuan.create',
            'temuan.validate_v1',
        ]);

        // Role Validator 2 — Senior Manager
        $validator2 = Role::firstOrCreate(['name' => 'validator_2']);
        $validator2->givePermissionTo([
            'dashboard.view',
            'hiradc.view',
            'hiradc.validate_v2',
            'program_kerja.view',
            'live_audit.view',
            'live_audit.validate_v2',
            'temuan.view',
            'temuan.create',
            'temuan.validate_v2',
        ]);

        // Role Pelapor Temuan — Staf/Pekerja
        $pelapor = Role::firstOrCreate(['name' => 'pelapor_temuan']);
        $pelapor->givePermissionTo([
            'dashboard.view',
            'temuan.view',
            'temuan.create',
        ]);
    }
}
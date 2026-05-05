<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@pltu.com'],
            [
                'name' => 'Admin HSSE',
                'password' => Hash::make('password'),
                'nip' => '1001',
                'jabatan' => 'Officer HSSE',
                'no_hp' => '08111111111',
                'is_active' => true,
            ]
        );
        $admin->assignRole('admin');

        // Validator 1
        $v1 = User::firstOrCreate(
            ['email' => 'validator1@pltu.com'],
            [
                'name' => 'Asisten Manajer K3',
                'password' => Hash::make('password'),
                'nip' => '1002',
                'jabatan' => 'Asisten Manajer K3',
                'no_hp' => '08222222222',
                'is_active' => true,
            ]
        );
        $v1->assignRole('validator_1');

        // Validator 2
        $v2 = User::firstOrCreate(
            ['email' => 'validator2@pltu.com'],
            [
                'name' => 'Senior Manager',
                'password' => Hash::make('password'),
                'nip' => '1003',
                'jabatan' => 'Senior Manager',
                'no_hp' => '08333333333',
                'is_active' => true,
            ]
        );
        $v2->assignRole('validator_2');

        // Pelapor Temuan
        $pelapor = User::firstOrCreate(
            ['email' => 'pelapor@pltu.com'],
            [
                'name' => 'Staf Lapangan',
                'password' => Hash::make('password'),
                'nip' => '1004',
                'jabatan' => 'Staf Lapangan',
                'no_hp' => '08444444444',
                'is_active' => true,
            ]
        );
        $pelapor->assignRole('pelapor_temuan');
    }
}
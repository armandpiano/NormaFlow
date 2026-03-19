<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Company;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get tenant
        $tenant = \App\Models\Tenant::where('slug', 'demo-empresa')->first();
        
        if (!$tenant) {
            return;
        }

        // Create super admin
        User::updateOrCreate(
            ['email' => 'admin@normaflow.com', 'tenant_id' => $tenant->id],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Create company admin
        User::updateOrCreate(
            ['email' => 'admin@demo.com', 'tenant_id' => $tenant->id],
            [
                'name' => 'Demo Admin',
                'password' => Hash::make('password'),
                'role' => 'company_admin',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Create auditor
        User::updateOrCreate(
            ['email' => 'auditor@demo.com', 'tenant_id' => $tenant->id],
            [
                'name' => 'Carlos Auditor',
                'password' => Hash::make('password'),
                'role' => 'auditor',
                'position' => 'Auditor Interno',
                'department' => 'Calidad',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Create employee
        User::updateOrCreate(
            ['email' => 'empleado@demo.com', 'tenant_id' => $tenant->id],
            [
                'name' => 'Juan Empleado',
                'password' => Hash::make('password'),
                'role' => 'employee',
                'position' => 'Operador de Producción',
                'department' => 'Operaciones',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}

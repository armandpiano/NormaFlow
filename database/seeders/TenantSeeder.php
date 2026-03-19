<?php

namespace Database\Seeders;

use App\Models\Tenant;
use Illuminate\Database\Seeder;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create demo tenant
        Tenant::create([
            'name' => 'Demo Empresa',
            'slug' => 'demo-empresa',
            'domain' => 'demo.normaflow.com',
            'settings' => [
                'timezone' => 'America/Mexico_City',
                'currency' => 'MXN',
                'notifications_enabled' => true,
            ],
            'subscription_plan' => 'growth',
            'status' => 'active',
            'subscription_starts_at' => now(),
            'subscription_ends_at' => now()->addYear(),
        ]);

        // Create additional demo tenants
        Tenant::create([
            'name' => 'Acme Corporation',
            'slug' => 'acme-corp',
            'settings' => [
                'timezone' => 'America/Mexico_City',
                'currency' => 'MXN',
            ],
            'subscription_plan' => 'starter',
            'status' => 'active',
            'subscription_starts_at' => now(),
            'subscription_ends_at' => now()->addMonth(),
        ]);
    }
}

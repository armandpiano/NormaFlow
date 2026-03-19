<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define roles
        $roles = [
            [
                'name' => 'Super Admin',
                'slug' => 'super_admin',
                'description' => 'System administrator with full access',
                'guard_name' => 'web',
            ],
            [
                'name' => 'Company Admin',
                'slug' => 'company_admin',
                'description' => 'Company administrator with full access within company',
                'guard_name' => 'web',
            ],
            [
                'name' => 'Site Manager',
                'slug' => 'site_manager',
                'description' => 'Site-level administrator',
                'guard_name' => 'web',
            ],
            [
                'name' => 'Auditor',
                'slug' => 'auditor',
                'description' => 'Internal or external auditor',
                'guard_name' => 'web',
            ],
            [
                'name' => 'Employee',
                'slug' => 'employee',
                'description' => 'Regular employee with limited access',
                'guard_name' => 'web',
            ],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->updateOrInsert(
                ['slug' => $role['slug']],
                $role
            );
        }

        // Define permissions
        $permissions = [
            // Companies
            ['name' => 'companies.view', 'slug' => 'companies.view', 'description' => 'View companies', 'group' => 'Companies'],
            ['name' => 'companies.create', 'slug' => 'companies.create', 'description' => 'Create companies', 'group' => 'Companies'],
            ['name' => 'companies.update', 'slug' => 'companies.update', 'description' => 'Update companies', 'group' => 'Companies'],
            ['name' => 'companies.delete', 'slug' => 'companies.delete', 'description' => 'Delete companies', 'group' => 'Companies'],

            // Sites
            ['name' => 'sites.view', 'slug' => 'sites.view', 'description' => 'View sites', 'group' => 'Sites'],
            ['name' => 'sites.create', 'slug' => 'sites.create', 'description' => 'Create sites', 'group' => 'Sites'],
            ['name' => 'sites.update', 'slug' => 'sites.update', 'description' => 'Update sites', 'group' => 'Sites'],
            ['name' => 'sites.delete', 'slug' => 'sites.delete', 'description' => 'Delete sites', 'group' => 'Sites'],

            // Users
            ['name' => 'users.view', 'slug' => 'users.view', 'description' => 'View users', 'group' => 'Users'],
            ['name' => 'users.create', 'slug' => 'users.create', 'description' => 'Create users', 'group' => 'Users'],
            ['name' => 'users.update', 'slug' => 'users.update', 'description' => 'Update users', 'group' => 'Users'],
            ['name' => 'users.delete', 'slug' => 'users.delete', 'description' => 'Delete users', 'group' => 'Users'],

            // Regulations
            ['name' => 'regulations.view', 'slug' => 'regulations.view', 'description' => 'View regulations', 'group' => 'Compliance'],
            ['name' => 'regulations.manage', 'slug' => 'regulations.manage', 'description' => 'Manage regulations', 'group' => 'Compliance'],

            // Evidences
            ['name' => 'evidences.view', 'slug' => 'evidences.view', 'description' => 'View evidences', 'group' => 'Compliance'],
            ['name' => 'evidences.upload', 'slug' => 'evidences.upload', 'description' => 'Upload evidences', 'group' => 'Compliance'],
            ['name' => 'evidences.approve', 'slug' => 'evidences.approve', 'description' => 'Approve evidences', 'group' => 'Compliance'],

            // Audits
            ['name' => 'audits.view', 'slug' => 'audits.view', 'description' => 'View audits', 'group' => 'Audits'],
            ['name' => 'audits.create', 'slug' => 'audits.create', 'description' => 'Create audits', 'group' => 'Audits'],
            ['name' => 'audits.manage', 'slug' => 'audits.manage', 'description' => 'Manage audits', 'group' => 'Audits'],

            // Reports
            ['name' => 'reports.view', 'slug' => 'reports.view', 'description' => 'View reports', 'group' => 'Reports'],
            ['name' => 'reports.export', 'slug' => 'reports.export', 'description' => 'Export reports', 'group' => 'Reports'],
        ];

        foreach ($permissions as $permission) {
            DB::table('permissions')->updateOrInsert(
                ['slug' => $permission['slug']],
                $permission
            );
        }
    }
}

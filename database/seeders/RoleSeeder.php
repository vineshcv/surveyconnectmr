<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'Super Admin']);
        $admin = Role::create(['name' => 'Admin']);
        $productManager = Role::create(['name' => 'Product Manager']);

        $admin->givePermissionTo([
            'create-user',
            'edit-user',
            'delete-user',
            'create-product',
            'edit-product',
            'delete-product',
            'create-client',
            'edit-client',
            'create-vendor',
            'edit-vendor',
            'delete-vendor',
            'create-questions',
            'edit-questions',
            'delete-questions',
            'create-projects',
            'edit-projects',
            'delete-projects',
            'create-invoices',
            'edit-invoices',
            'delete-invoices',
            'create-clients',
            'edit-clients',
            'delete-clients',
            // Vendor Registration permissions
            'view-vendor-registrations',
            'approve-vendor-registrations',
            'reject-vendor-registrations',
            'manage-vendor-registrations',
        ]);

        $productManager->givePermissionTo([
            'create-product',
            'edit-product',
            'delete-product',
            'view-vendor-registrations',
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    $permissions = [
        'create-role',
        'edit-role',
        'delete-role',
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
    ];

    foreach ($permissions as $permission) {
        Permission::firstOrCreate(['name' => $permission]);
    }
}

}






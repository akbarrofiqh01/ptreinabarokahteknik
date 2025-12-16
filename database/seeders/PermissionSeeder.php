<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{

    public function run(): void
    {
        $permissions = [
            // TRANSACTION
            'transaction.create',
            'transaction.view',
            'transaction.update',
            'transaction.submit',
            'transaction.approve',
            'transaction.reject',

            // TRANSACTION ITEM
            'transaction-item.add',
            'transaction-item.update',
            'transaction-item.delete',
            'transaction-item.import-excel',
            'transaction-item.check-ready',

            // PAYMENT
            'payment.upload',
            'payment.view',
            'payment.verify',
            'payment.reject',

            // LEDGER / REPORT
            'ledger.view',

            // LOG
            'transaction-log.view',

            'view dashboard',
            'view roles',
            'create roles',
            'edit roles',
            'delete roles',
            'view permissions',
            'create permissions',
            'edit permissions',
            'delete permissions',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web', 'code_permissions' => Str::random(60)]);
        }

        $adminRole = Role::firstOrCreate(['name' => 'admin', 'code_role' => Str::random(60)]);
        $userRole  = Role::firstOrCreate(['name' => 'user', 'code_role' => Str::random(60)]);

        $userRole->syncPermissions([
            'transaction.create',
            'transaction.view',
            'transaction.update',
            'transaction.submit',

            'transaction-item.add',
            'transaction-item.update',
            'transaction-item.delete',
            'transaction-item.import-excel',

            'payment.upload',
            'payment.view',
        ]);

        $adminRole->syncPermissions([
            'transaction.view',
            'transaction.approve',
            'transaction.reject',

            'transaction-item.check-ready',

            'payment.view',
            'payment.verify',
            'payment.reject',

            'ledger.view',
            'transaction-log.view',
        ]);

        $superAdmin = Role::firstOrCreate(['name' => 'superadmin', 'guard_name' => 'web', 'code_role'    => Str::random(60)]);
        $superAdmin->syncPermissions(Permission::all());
    }
}

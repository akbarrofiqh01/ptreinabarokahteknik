<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
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

        // $roleAdmin = Role::create(['name' => 'admin', 'guard_name' => 'web', 'code_role'    => Str::random(60)]);
        // $roleAdmin->givePermissionTo(Permission::all());

        // $roleDosen = Role::create(['name' => 'dosen', 'guard_name' => 'web', 'code_role'    => Str::random(60)]);
        // $roleDosen->givePermissionTo([
        //     'view dashboard',
        //     'input nilai',
        //     'view laporan'
        // ]);

        // $roleMahasiswa = Role::create(['name' => 'mahasiswa', 'guard_name' => 'web', 'code_role'    => Str::random(60)]);
        // $roleMahasiswa->givePermissionTo([
        //     'view dashboard',
        //     'buat krs'
        // ]);

        // $roleKaprodi = Role::create(['name' => 'kaprodi', 'guard_name' => 'web', 'code_role'    => Str::random(60)]);
        // $roleKaprodi->givePermissionTo([
        //     'view dashboard',
        //     'approve krs',
        //     'view laporan'
        // ]);

        // $roleTendik = Role::create(['name' => 'tendik', 'guard_name' => 'web', 'code_role'    => Str::random(60)]);
        // $roleTendik->givePermissionTo([
        //     'view dashboard',
        //     'manage pengumuman'
        // ]);

        $superAdmin = Role::firstOrCreate(['name' => 'superadmin', 'guard_name' => 'web', 'code_role'    => Str::random(60)]);
        $superAdmin->syncPermissions(Permission::all());
    }
}

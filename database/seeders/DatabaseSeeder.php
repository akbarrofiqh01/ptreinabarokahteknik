<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $this->call([
            PermissionSeeder::class,
            UserSeeder::class,
        ]);
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'username' => 'superadmin',
            'phone' => '+6287752729835',
            'email' => 'superadmin@admin.com',
            'password' => Hash::make('password'),
            'code_user' => Str::random(60),
        ]);
        $superAdmin->assignRole('superadmin');
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Company;
use Spatie\Permission\Models\Role;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Ambil role user
        $userRole = Role::where('name', 'user')->first();

        if (!$userRole) {
            $this->command->error('Role "user" belum ada. Jalankan PermissionSeeder terlebih dahulu.');
            return;
        }

        // Ambil semua company
        $companies = Company::pluck('id');

        if ($companies->isEmpty()) {
            $this->command->error('Data company belum ada. Jalankan CompanySeeder terlebih dahulu.');
            return;
        }

        $totalUser = 20;

        for ($i = 0; $i < $totalUser; $i++) {

            $user = User::create([
                'name'       => $faker->name,
                'username'   => $faker->unique()->userName,
                'nik'        => $faker->numerify('################'),
                'phone'      => $faker->numerify('08##########'),
                'email'      => $faker->unique()->safeEmail,
                'password'   => Hash::make('password'),
                'company_id' => $companies->random(), // 👈 RELASI COMPANY
                'code_user'  => Str::random(60),
                // 'source'     => 'register',
                // 'status'     => 'active',
                'is_active'  => true,
            ]);

            // Assign role
            $user->assignRole($userRole);
        }

        $this->command->info('Seeder user + company berhasil dijalankan.');
    }
}

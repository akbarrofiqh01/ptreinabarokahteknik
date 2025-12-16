<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $totalUser = 20;
        for ($i = 0; $i < $totalUser; $i++) {

            User::create([
                'name'      => $faker->name,
                'username'  => $faker->unique()->userName,
                'phone'     => '+62' . $faker->numerify('##########'),
                'email'     => $faker->unique()->safeEmail,
                'password'  => Hash::make('password'),
                'code_user' => Str::random(60),
            ]);
        }
    }
}

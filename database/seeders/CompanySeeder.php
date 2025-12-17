<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = [
            [
                'name'    => 'PT Maju Jaya Sejahtera',
                'npwp'    => '01.234.567.8-901.000',
                'phone'   => '021-5551234',
                'address' => 'Jl. Sudirman No. 10, Jakarta Pusat',
                'code_company' => Str::random(60)
            ],
            [
                'name'    => 'CV Sinar Abadi',
                'npwp'    => '02.345.678.9-012.000',
                'phone'   => '022-7778899',
                'address' => 'Jl. Asia Afrika No. 25, Bandung',
                'code_company' => Str::random(60)
            ],
            [
                'name'    => 'PT Teknologi Nusantara',
                'npwp'    => '03.456.789.0-123.000',
                'phone'   => '031-8887766',
                'address' => 'Jl. Raya Darmo No. 5, Surabaya',
                'code_company' => Str::random(60)
            ],
            [
                'name'    => 'PT Global Solusi Indonesia',
                'npwp'    => '04.567.890.1-234.000',
                'phone'   => '0274-556677',
                'address' => 'Jl. Malioboro No. 99, Yogyakarta',
                'code_company' => Str::random(60)
            ],
            [
                'name'    => 'PT Berkah Mandiri Utama',
                'npwp'    => '05.678.901.2-345.000',
                'phone'   => '061-4455667',
                'address' => 'Jl. Gatot Subroto No. 12, Medan',
                'code_company' => Str::random(60)
            ],
        ];

        foreach ($companies as $company) {
            Company::create($company);
        }
    }
}

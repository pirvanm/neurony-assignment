<?php

namespace Database\Seeders;

use App\Models\Candidate;
use App\Models\Company;
use App\Models\Wallet;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Candidate::factory(5)->create();
        Candidate::factory(5)->create([
            'is_mvp' => true,
        ]);
        Company::factory(1)->create();
        Wallet::factory(1)->create();
    }
}

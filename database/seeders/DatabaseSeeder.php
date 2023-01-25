<?php

namespace Database\Seeders;

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
        \App\Models\User::factory()->count(10)->create();
        \App\Models\Country::factory()->count(10)->create();
        /*\App\Models\Company::factory()->count(50)->create();
        \App\Models\CompanyUsers::factory()->count(50)->create();*/
    }
}

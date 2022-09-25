<?php

namespace Database\Seeders;

use App\Models\donate_admin;
use Illuminate\Database\Seeder;

class DonateAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $donate = [
            ['donate_id' => 2 , 'user_id' => 1],
        ];

        donate_admin::insert($donate);
    }
}

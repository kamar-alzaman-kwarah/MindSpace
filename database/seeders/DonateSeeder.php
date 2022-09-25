<?php

namespace Database\Seeders;

use App\Models\donate;
use Illuminate\Database\Seeder;

class DonateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $donates = [
            ['user_id' => 4 , 'name' => 'rawan' , 'phone_number' => '0949109803'],
            ['user_id' => 4 , 'name' => 'rawan' , 'phone_number' => '0949109803'],
        ];

        donate::insert($donates);
    }
}

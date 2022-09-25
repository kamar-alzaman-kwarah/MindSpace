<?php

namespace Database\Seeders;

use App\Models\amateure_admin;
use Illuminate\Database\Seeder;

class AmateureAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $amateure = [
            ['user_id' => 1 , 'amateure_id' => 1]
        ];

        amateure_admin::insert($amateure);
    }
}

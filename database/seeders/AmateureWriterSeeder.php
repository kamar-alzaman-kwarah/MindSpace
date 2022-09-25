<?php

namespace Database\Seeders;

use App\Models\amateure_writer;
use Illuminate\Database\Seeder;

class AmateureWriterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $amateure =[
            ['name' => 'اشياء غريبة يقولها الزبائن في متجر الكتب' , 'description' => 'anything....' ,'phone_number' => '0949109803' , 'user_id' => 4 , 'pdf' => 'أشياء_غريبة_يقولها_الزبائن_في_متجر_الكتب__جين_كاميل.pdf'],
            ['name' => 'اشياء غريبة يقولها الزبائن في متجر الكتب' , 'description' => 'anything....' ,'phone_number' => '0949109803' , 'user_id' => 4 , 'pdf' => 'أشياء_غريبة_يقولها_الزبائن_في_متجر_الكتب__جين_كاميل.pdf'],
        ];

        amateure_writer::insert($amateure);
    }
}

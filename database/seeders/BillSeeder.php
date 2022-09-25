<?php

namespace Database\Seeders;

use App\Models\bill;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $bills = [
            ['cart_id' => 4 , 'address_id' => 6 ,'phone_number' => '0949109803' ,'state' => 0 , 'user_id'=> 7 , 'created_at' => Carbon::now()],
            ['cart_id' => 21 , 'address_id' => 6 ,'phone_number' => '0949109803' ,'state' => 1 , 'user_id'=> 7 , 'created_at' => Carbon::now()],
        ];

        bill::insert($bills);
    }
}

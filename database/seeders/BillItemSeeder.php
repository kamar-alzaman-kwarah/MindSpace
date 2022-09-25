<?php

namespace Database\Seeders;

use App\Models\bill_item;
use Illuminate\Database\Seeder;

class BillItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bill_items = [
            ['bill_id' => 1 , 'item_id' => 1 , 'price' => 6000 , 'donate_cart_id' => null ],
            ['bill_id' => 1 , 'item_id' => 2 , 'price' => 1500 , 'donate_cart_id' => null ],
            ['bill_id' => 1 , 'item_id' => 3 , 'price' => 3500 , 'donate_cart_id' => null ],
            ['bill_id' => 1 , 'item_id' => 4 , 'price' => 6000 , 'donate_cart_id' => null ],
            ['bill_id' => 1 , 'item_id' => 5 , 'price' => 1500 , 'donate_cart_id' => null ],
        ];

        bill_item::insert($bill_items);
    }
}

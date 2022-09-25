<?php

namespace Database\Seeders;

use App\Models\item;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items =[
            ['cart_id'=> 4 , 'book_id'=>1 , 'quantity' => 2],
            ['cart_id'=> 4 , 'book_id'=>2 , 'quantity' => 1],
            ['cart_id'=> 4 , 'book_id'=>4 , 'quantity' => 1],
            ['cart_id'=> 21, 'book_id'=>1 , 'quantity' => 2],
            ['cart_id'=> 21 , 'book_id'=>2 , 'quantity' => 1],
        ];

        item::insert($items);
    }
}

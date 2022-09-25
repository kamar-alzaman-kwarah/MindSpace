<?php

namespace Database\Seeders;

use App\Models\book_donate;
use Illuminate\Database\Seeder;

class BookDonateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $books =[
            ['donate_id' => 1 , 'name'=>'مئة عام من العزلة' ,'photo'=>'مئة عام من العزلة.jpg' , 'acceptance' => 0 , 'state' => 0 ],
            ['donate_id' => 1 , 'name'=> "قصص روسية" ,'photo'=>'قصص روسية.png' , 'acceptance' => 0 , 'state' => 0 ],
            ['donate_id' => 1 , 'name'=>'عصر العلم' ,'photo'=>'عصر العلم.jpg' , 'acceptance' => 0 , 'state' => 0 ],
            ['donate_id' => 2 , 'name'=>'مئة عام من العزلة' ,'photo'=>'مئة عام من العزلة.jpg' , 'acceptance' => 1 , 'state' => 0 ],
            ['donate_id' => 2 , 'name'=> "قصص روسية" ,'photo'=>'قصص روسية.png' , 'acceptance' => 1 , 'state' => 0 ],
            ['donate_id' => 2 , 'name'=>'عصر العلم' ,'photo'=>'عصر العلم.jpg' , 'acceptance' => 1 , 'state' => 0 ],
        ];

        book_donate::insert($books);
    }
}

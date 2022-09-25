<?php

namespace Database\Seeders;
use App\Models\category_user;
use Illuminate\Database\Seeder;

class CategoryUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories_user=[
            [
                'user_id'=>12 ,
                'category_id'=>1

            ],
            [
                'user_id'=>12 ,
                'category_id'=>2

            ],
            [
                'user_id'=>12 ,
                'category_id'=>3

            ],
            [
                'user_id'=>12 ,
                'category_id'=>4

            ],
            [
                'user_id'=>13 ,
                'category_id'=>5

            ],
            [
                'user_id'=>13 ,
                'category_id'=>6

            ],
            [
                'user_id'=>13 ,
                'category_id'=>7

            ],
            [
                'user_id'=>13 ,
                'category_id'=>8

            ],
            [
                'user_id'=>14 ,
                'category_id'=>9

            ],
            [
                'user_id'=>14 ,
                'category_id'=>10

            ],
            [
                'user_id'=>14 ,
                'category_id'=>11

            ],
            [
                'user_id'=>14 ,
                'category_id'=>12

            ],
            [
                'user_id'=>15 ,
                'category_id'=>13

            ],
            [
                'user_id'=>15 ,
                'category_id'=>14

            ],
            [
                'user_id'=>15 ,
                'category_id'=>15

            ],
            [
                'user_id'=>15 ,
                'category_id'=>1

            ],
            [
                'user_id'=>16 ,
                'category_id'=>2

            ],
            [
                'user_id'=>16 ,
                'category_id'=>3

            ],
            [
                'user_id'=>16 ,
                'category_id'=>4

            ],
            [
                'user_id'=>16 ,
                'category_id'=>5

            ],
            [
                'user_id'=>17 ,
                'category_id'=>6

            ],
            [
                'user_id'=>17 ,
                'category_id'=>7

            ],
            [
                'user_id'=>17 ,
                'category_id'=>8

            ],
            [
                'user_id'=>17 ,
                'category_id'=>9

            ],
            [
                'user_id'=>18 ,
                'category_id'=>10

            ],
            [
                'user_id'=>18 ,
                'category_id'=>1

            ],
            [
                'user_id'=>18 ,
                'category_id'=>7

            ],
            [
                'user_id'=>18 ,
                'category_id'=>5

            ],
            [
                'user_id'=>19 ,
                'category_id'=>12

            ],
            [
                'user_id'=>19 ,
                'category_id'=>8

            ],
            [
                'user_id'=>19 ,
                'category_id'=>6

            ],
            [
                'user_id'=>19 ,
                'category_id'=>4

            ],
            [
                'user_id'=>20 ,
                'category_id'=>3

            ],
            [
                'user_id'=>20 ,
                'category_id'=>9

            ],
            [
                'user_id'=>20 ,
                'category_id'=>7

            ],
            [
                'user_id'=>20 ,
                'category_id'=>4

            ],
            [
                'user_id'=>21 ,
                'category_id'=>2

            ],
            [
                'user_id'=>21 ,
                'category_id'=>5

            ],
            [
                'user_id'=>21 ,
                'category_id'=>6

            ],
            [
                'user_id'=>21 ,
                'category_id'=>8

            ],
        ];
        category_user::insert($categories_user);
    }
}

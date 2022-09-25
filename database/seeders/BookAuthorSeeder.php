<?php

namespace Database\Seeders;

use App\Models\book_author;
use Illuminate\Database\Seeder;

class BookAuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $book_author = [
            ['author_id'=>'1', 'book_id'=>'1'],
            ['author_id'=>'2', 'book_id'=>'2'],
            ['author_id'=>'3', 'book_id'=>'3'],
            ['author_id'=>'4', 'book_id'=>'4'],
            ['author_id'=>'5', 'book_id'=>'4'],
            ['author_id'=>'6', 'book_id'=>'4'],
            ['author_id'=>'7', 'book_id'=>'5'],
            ['author_id'=>'8', 'book_id'=>'6'],
            ['author_id'=>'9', 'book_id'=>'7'],
            ['author_id'=>'10', 'book_id'=>'8'],
            ['author_id'=>'11', 'book_id'=>'9'],
            ['author_id'=>'12', 'book_id'=>'10'],
            ['author_id'=>'13', 'book_id'=>'11'],
            ['author_id'=>'14', 'book_id'=>'12'],
            ['author_id'=>'15', 'book_id'=>'13'],
            ['author_id'=>'16', 'book_id'=>'14'],
        ];

        book_author::insert($book_author);
    }
}

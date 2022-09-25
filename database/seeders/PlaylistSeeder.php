<?php

namespace Database\Seeders;

use App\Models\playlist;
use Illuminate\Database\Seeder;

class PlaylistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $playlists =[
            ['name' => "private" , 'state' => 1 , 'user_id' => 1],
            ['name' => "favorite" , 'state' => 0 , 'user_id' => 1],

            ['name' => "private" , 'state' => 1 , 'user_id' => 2],
            ['name' => "favorite" , 'state' => 0 , 'user_id' => 2],

            ['name' => "private" , 'state' => 1 , 'user_id' => 3],
            ['name' => "favorite" , 'state' => 0 , 'user_id' => 3],

            ['name' => "private" , 'state' => 1 , 'user_id' => 4],
            ['name' => "favorite" , 'state' => 0 , 'user_id' => 4],

            ['name' => "private" , 'state' => 1 , 'user_id' => 5],
            ['name' => "favorite" , 'state' => 0 , 'user_id' => 5],

            ['name' => "private" , 'state' => 1 , 'user_id' => 6],
            ['name' => "favorite" , 'state' => 0 , 'user_id' => 6],

            ['name' => "private" , 'state' => 1 , 'user_id' => 7],
            ['name' => "favorite" , 'state' => 0 , 'user_id' => 7],

            ['name' => "private" , 'state' => 1 , 'user_id' => 8],
            ['name' => "favorite" , 'state' => 0 , 'user_id' => 8],

            ['name' => "private" , 'state' => 1 , 'user_id' => 9],
            ['name' => "favorite" , 'state' => 0 , 'user_id' => 9],

            ['name' => "private" , 'state' => 1 , 'user_id' => 10],
            ['name' => "favorite" , 'state' => 0 , 'user_id' => 10],

            ['name' => "private" , 'state' => 1 , 'user_id' => 11],
            ['name' => "favorite" , 'state' => 0 , 'user_id' => 11],

            ['name' => "private" , 'state' => 1 , 'user_id' => 12],
            ['name' => "favorite" , 'state' => 0 , 'user_id' => 12],

            ['name' => "private" , 'state' => 1 , 'user_id' => 13],
            ['name' => "favorite" , 'state' => 0 , 'user_id' => 13],

            ['name' => "private" , 'state' => 1 , 'user_id' => 14],
            ['name' => "favorite" , 'state' => 0 , 'user_id' => 14],

            ['name' => "private" , 'state' => 1 , 'user_id' => 15],
            ['name' => "favorite" , 'state' => 0 , 'user_id' => 15],

            ['name' => "private" , 'state' => 1 , 'user_id' => 16],
            ['name' => "favorite" , 'state' => 0 , 'user_id' => 16],

            ['name' => "private" , 'state' => 1 , 'user_id' => 17],
            ['name' => "favorite" , 'state' => 0 , 'user_id' => 17],

            ['name' => "private" , 'state' => 1 , 'user_id' => 18],
            ['name' => "favorite" , 'state' => 0 , 'user_id' => 18],

            ['name' => "private" , 'state' => 1 , 'user_id' => 19],
            ['name' => "favorite" , 'state' => 0 , 'user_id' => 19],

            ['name' => "private" , 'state' => 1 , 'user_id' => 20],
            ['name' => "favorite" , 'state' => 0 , 'user_id' => 20],

            ['name' => "private" , 'state' => 1 , 'user_id' => 21],
            ['name' => "favorite" , 'state' => 0 , 'user_id' => 21],
        ];

        playlist::insert($playlists);
    }
}

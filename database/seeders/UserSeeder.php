<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users =[
            ['first_name' => 'laila' ,
            'last_name' => 'abbas' ,
            'email' => 'everythingismeaningless333@gmail.com',
            'email_verified_at' => Carbon::now(),
            'activated' => 0,
            'password' =>  Hash::make('aevugetkrmgdttbg'),
            'role_id' => 3 ,
            'address_id' => 6] ,

           [ 'first_name' => 'ali' ,
            'last_name' => 'ajloni' ,
            'email' => 'ali.ajj2000@gmail.com',
            'email_verified_at' => Carbon::now(),
            'activated' => 0,
            'password' =>  Hash::make('alialiali'),
            'role_id' => 2 ,
            'address_id' => 1 ],

            ['first_name' => 'aya' ,
            'last_name' => 'almasri' ,
            'email' => 'ayah.almasri2001@gmail.com',
            'email_verified_at' => Carbon::now(),
            'activated' => 0,
            'password' =>  Hash::make('@Ayoosh123'),
            'role_id' => 2 ,
            'address_id' => 2] ,

            ['first_name' => 'rawan' ,
            'last_name' => 'almasri' ,
            'email' => 'Rawan.mas.2001@gmail.com',
            'email_verified_at' => Carbon::now(),
            'activated' => 0,
            'password' =>  Hash::make('rawan_almasri'),
            'role_id' => 2 ,
            'address_id' => 3] ,

            ['first_name' => 'kamar alzaman' ,
            'last_name' => 'kwara' ,
            'email' => 'reda.9986@gmail.com',
            'email_verified_at' => Carbon::now(),
            'activated' => 0,
            'password' =>  Hash::make('kamar_alzaman_kwara'),
            'role_id' => 2 ,
            'address_id' => 4] ,

            ['first_name' => 'hiam' ,
            'last_name' => 'alasaad' ,
            'email' => 'hiamalasaad001@gmail.com',
            'email_verified_at' => Carbon::now(),
            'activated' => 0,
            'password' =>  Hash::make('hiam_alasaad'),
            'role_id' => 2 ,
            'address_id' => 5] ,

            ['first_name' => 'ahmad' ,
            'last_name' => 'hasan' ,
            'email' => 'ahmadhasan001@gmail.com',
            'email_verified_at' => Carbon::now(),
            'activated' => 0,
            'password' =>  Hash::make('ahmad_hasan'),
            'role_id' => 4 ,
            'address_id' => 6] ,

            ['first_name' => 'mahmood' ,
            'last_name' => 'daabool' ,
            'email' => 'mahmoodaabool001@gmail.com',
            'email_verified_at' => Carbon::now(),
            'activated' => 0,
            'password' =>  Hash::make('mahmood_daabool'),
            'role_id' => 4 ,
            'address_id' => 8] ,

            ['first_name' => 'samer' ,
            'last_name' => 'sameer' ,
            'email' => 'samersameer001@gmail.com',
            'email_verified_at' => Carbon::now(),
            'activated' => 0,
            'password' =>  Hash::make('samer_sameer'),
            'role_id' => 4 ,
            'address_id' => 9] ,

            ['first_name' => 'jameel' ,
            'last_name' => 'jalal' ,
            'email' => 'jameeljalal001@gmail.com',
            'email_verified_at' => Carbon::now(),
            'activated' => 0,
            'password' =>  Hash::make('jameel_jalal'),
            'role_id' => 4 ,
            'address_id' => 5] ,

            ['first_name' => 'hassan' ,
            'last_name' => 'mohsen' ,
            'email' => 'hassanmohsen001@gmail.com',
            'email_verified_at' => Carbon::now(),
            'activated' => 0,
            'password' =>  Hash::make('hassan_mohsen'),
            'role_id' => 4 ,
            'address_id' => 10] ,


             ['first_name' => 'madiha' ,
            'last_name' => 'alaashoor' ,
            'email' => 'omwissam63@gmail.com',
            'email_verified_at' => Carbon::now(),
            'activated' => 0,
            'password' =>  Hash::make('madiha_alaashoor'),
            'role_id' => 1 ,
            'address_id' => 4] ,

            ['first_name' => 'ayman' ,
            'last_name' => 'ahleel' ,
            'email' => 'aymanahleel004@gmail.com',
            'email_verified_at' => Carbon::now(),
            'activated' => 0,
            'password' =>  Hash::make('ayman_ahleel'),
            'role_id' => 1 ,
            'address_id' => 9] ,

            ['first_name' => 'rama' ,
            'last_name' => 'ahleel' ,
            'email' => 'ramaahleel001@gmail.com',
            'email_verified_at' => Carbon::now(),
            'activated' => 0,
            'password' =>  Hash::make('rama_ahleel'),
            'role_id' => 1 ,
            'address_id' => 8] ,

            ['first_name' => 'lilian' ,
            'last_name' => 'kasem' ,
            'email' => 'liliankasem001@gmail.com',
            'email_verified_at' => Carbon::now(),
            'activated' => 0,
            'password' =>  Hash::make('liliankasem'),
            'role_id' => 1 ,
            'address_id' => 3] ,

            ['first_name' => 'majd' ,
            'last_name' => 'almasri' ,
            'email' => 'majdalmasri92@gmail.com',
            'email_verified_at' => Carbon::now(),
            'activated' => 0,
            'password' =>  Hash::make('majd_almasri'),
            'role_id' => 1 ,
            'address_id' => 4] ,

            ['first_name' => 'alaa' ,
            'last_name' => 'almasri' ,
            'email' => 'alaa.almasri88@gmail.com',
            'email_verified_at' => Carbon::now(),
            'activated' => 0,
            'password' =>  Hash::make('alaa_almasri'),
            'role_id' => 1 ,
            'address_id' => 6] ,

            ['first_name' => 'wissam' ,
            'last_name' => 'almasri' ,
            'email' => 'lord.wissam87@gmail.com',
            'email_verified_at' => Carbon::now(),
            'activated' => 0,
            'password' =>  Hash::make('wissam_almasri'),
            'role_id' => 1 ,
            'address_id' => 11] ,

            ['first_name' => 'abdalfattah' ,
            'last_name' => 'alhajbaker' ,
            'email' => 'abdalfattah001@gmail.com',
            'email_verified_at' => Carbon::now(),
            'activated' => 0,
            'password' =>  Hash::make('abdalfattah_alhajbaker'),
            'role_id' => 1 ,
            'address_id' => 10] ,

            ['first_name' => 'salem' ,
            'last_name' => 'alaashoor' ,
            'email' => 'salem.alaasshoor2000@gmail.com',
            'email_verified_at' => Carbon::now(),
            'activated' => 0,
            'password' =>  Hash::make('salem_alaashoor'),
            'role_id' => 1 ,
            'address_id' => 7] ,

            ['first_name' => 'hasan' ,
            'last_name' => 'hajbaker' ,
            'email' => 'hasanhaj.001@gmail.com',
            'email_verified_at' => Carbon::now(),
            'activated' => 0,
            'password' =>  Hash::make('hasan_hajbaker'),
            'role_id' => 1 ,
            'address_id' => 1] ,
        ];

        User::insert($users);
    }
}

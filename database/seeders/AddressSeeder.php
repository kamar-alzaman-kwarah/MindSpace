<?php

namespace Database\Seeders;

use App\Models\address;
use Illuminate\Database\Seeder;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $addresses=[
            [
                'country' => 'syria' ,
                'state' => 'damascus',
                'city' => 'mazah',
                'street' => 'shekh'
            ],
            [
                'country' => 'syria' ,
                'state' => 'damascus',
                'city' => 'masaken barzeh',
                'street' => 'ibn alnafees'
            ],
            [
                'country' => 'syria' ,
                'state' => 'damascus',
                'city' => 'almojtahed',
                'street' => 'khaled'
            ],
            [
                'country' => 'syria' ,
                'state' => 'damascus countryside',
                'city' => 'zamalka',
                'street' => 'saha'
            ],
            [
                'country' => 'syria' ,
                'state' => 'damascus countryside',
                'city' => 'jamraya',
                'street' => 'bohoth'
            ],
            [
                'country' => 'syria' ,
                'state' => 'damascus countryside',
                'city' => 'sahnaya',
                'street' => 'tayarah'
            ],
            [
                'country' => 'syria' ,
                'state' => 'daraa',
                'city' => 'atman',
                'street' => 'banorama'
            ],
            [
                'country' => 'syria' ,
                'state' => 'damascus',
                'city' => 'abaseen',
                'street' => 'saha'
            ],
            [
                'country' => 'syria' ,
                'state' => 'hamah',
                'city' => 'mesyaf',
                'street' => 'allqubh'
            ],
            [
                'country' => 'syria' ,
                'state' => 'tartous',
                'city' => 'banyas',
                'street' => 'alqalaa'
            ],
            [
                'country' => 'syria' ,
                'state' => 'damascus',
                'city' => 'dwelaa',
                'street' => 'alhamak'
            ],
        ];

        address::insert($addresses);
    }
}

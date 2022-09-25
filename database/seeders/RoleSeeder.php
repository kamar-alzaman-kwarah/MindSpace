<?php

namespace Database\Seeders;

use App\Models\role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles =[
            [
                'role_name' => 'user'
            ],
            [
                'role_name' => 'admin'
            ],
            [
                'role_name' => 'super_admin'
            ],
            [
                'role_name' => 'shipper'
            ]
        ];

        role::insert($roles);

    }
}

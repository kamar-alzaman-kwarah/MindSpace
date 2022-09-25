<?php

namespace Database\Seeders;

use App\Models\book_donate;
use App\Models\donate;
use App\Models\donate_admin;
use App\Models\playlist;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //\App\Models\User::factory(10)->create();

        $this->call([
            AddressSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            CategorySeeder::class,
            CartSeeder::class,
            PlaylistSeeder::class,
            BookSeeder::class,
            CategoryBookSeeder::class,
            AuthorSeeder::class,
            BookAuthorSeeder::class,
            CategoryUserSeeder::class,
            ItemSeeder::class,
            BillSeeder::class,
            BillItemSeeder::class,
            DonateSeeder::class,
            BookDonateSeeder::class,
            DonateAdminSeeder::class,
            AmateureWriterSeeder::class,
            AmateureAdminSeeder::class,
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
            $categories =
            [
                ['name'=>'مجاني', 'image'=>'1- كتب مجانية.png'],
                ['name'=>'علمي', 'image'=>'2- كتب علمية.png'],
                ['name'=>'سيرة ذاتية', 'image'=>'3- سير ذاتية.png'],
                ['name'=>'إدارة أعمال', 'image'=>'4- كتب إدارة الأعمال.png'],
                ['name'=>'سياسي', 'image'=>'5- كتب سياسية.png'],
                ['name'=>'ديني', 'image'=>'6- كتب دينية.png'],
                ['name'=>'تاريخي', 'image'=>'7- كتب تاريخية.png'],
                ['name'=>'كتب اليافعين', 'image'=>'8- كتب اليافعين.png'],
                ['name'=>'قصص قصيرة', 'image'=>'9- قصص قصيرة.png'],
                ['name'=>'خيال علمي', 'image'=>'10- روايات الخيال العلمي.png'],
                ['name'=>'رومنسي', 'image'=>'11- روايات عاطفية.png'],
                ['name'=>'فانتازيا', 'image'=>'12- روايات الفانتازيا.png'],
                ['name'=>'مغامرة', 'image'=>'13- روايات المغامرة.png'],
                ['name'=>'بوليسي', 'image'=>'14- روايات بوليسية.png'],
                ['name'=>'واقعي نفسي', 'image'=>'15- روايات واقعية ونفسية.png'],
            ];
            category::insert($categories);
    }
}

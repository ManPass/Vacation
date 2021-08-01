<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public static int $housesCount = 10;
    public function run()
    {
        for($i = 0;$i < HouseSeeder::$housesCount;$i++) {
            $name = rand(1, 10) . '-' . rand(1, 10);
            try {
                DB::table('houses')->insert([
                    'name' => $name,
                    'description' => 'great house',
                    'beds_count' => rand(1, 3),
                    'has_electricity' => rand(0, 1),
                    'has_shower' => rand(0, 1)
                ]);
            }
            catch (\Exception $e){
                echo $name." name домика не уникально, домик пропускается\n";
            }
        }
    }
}

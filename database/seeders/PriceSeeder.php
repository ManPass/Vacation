<?php

namespace Database\Seeders;

use App\Models\Date;
use App\Models\House;
use Illuminate\Database\Seeder;

class PriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public static int $defaultPriceByWeek = 3500;
    public static int $defaultPriceByWeekEnd = 0;
    public function run()
    {
        $houses = House::all();
        $dates = Date::all();
        foreach($houses as $house){
            foreach ($dates as $date){
                $house->dates()->attach($date->id,['weekday_price'=>self::$defaultPriceByWeek,
                    'weekend_price'=>self::$defaultPriceByWeekEnd]);
            }
        }
    }
}

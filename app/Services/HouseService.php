<?php

namespace App\Services;

use App\Http\Controllers\PhotoController;
use App\Models\Date;
use App\Models\House;
use Database\Seeders\PriceSeeder;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Requests\HouseRequest;

class HouseService
{

    public static function getHouse($id)
    {
        return House::find($id);
    }

    public static function addHouse(HouseRequest $request){
        $house = House::create($request->house);
        foreach (Date::all() as $date){
            $house->dates()->attach($date->id,['weekday_price'=>PriceSeeder::$defaultPriceByWeek,
                'weekend_price'=>PriceSeeder::$defaultPriceByWeekEnd]);
        }

        PhotoController::insertPhoto($house, $request);
    }

    public static function checkHouseName($request)
    {
            foreach(House::all() as $house_el){
            if ($request->input('house.name') === $house_el->name)
                return false;
            }
            return true;
    }

    function getMinimumPrice()
    {

    }
}

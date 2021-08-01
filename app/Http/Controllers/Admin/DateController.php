<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PriceRequest;
use App\Models\Date;
use App\Models\House;
use Illuminate\Http\Request;

class DateController extends Controller
{
    /**
     * Возвращяет все зарегистрированные недели в рассписании для редактирования
     */
    public function getWeekScheduleView(Request $request){
        $house = House::find($request->id);
        return view('admin/schedule')->with(['house'=>['name'=>$house->name,'id'=>$house->id],'dates'=> Date::getDatesWithPrices($house)]);
    }
    public function updatePrice(PriceRequest $request){
        House::find($request['house_id'])->updatePriceByDate([['price'=>$request['weekend_price'],'week_part'=>'weekend_price'],
            ['price'=>$request['weekday_price'],'week_part'=>'weekday_price']],$request['date_id']);
        return redirect()->back();
    }

    public function getDatesDiapason($house)
    {
        $dates = [];
        foreach($house->dates as $date)
        {
            $dates["week_start"][] = new \DateTime($date);
            $dates["week_end"] = $dates["week_start"][count($dates["week_start"]) - 1]->modify('+ 6 days');
        }
    }
}

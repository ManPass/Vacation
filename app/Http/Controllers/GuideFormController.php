<?php

namespace App\Http\Controllers;

use App\Models\House;
use App\Models\Order;
use App\Services\Date\DateService;
use Illuminate\Http\Request;

class GuideFormController extends Controller
{
    public function showGuideForm(Request $request){

        return view('guide_form/guide_form',['dates'=>DateService::getDateByDaysAsDateUnit(),'houses'=>House::getNames(),
            'schedule'=>DateService::getScheduleByDay(),'currency'=>Order::$currency]);
    }
}

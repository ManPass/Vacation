<?php


namespace App\Services;


use App\Models\House;

class FakeDataService
{

    public static function getFakeScheduleByDay(){
        $houses = House::all();
        $data = ['занят','3500 руб'];
        $schedule=[];
        foreach($houses as $house){
            $dataCollection=[];
            for($i=0;$i<20;$i++)
                $dataCollection[] = $data[rand(0,1)];

            $schedule[$house->name] = $dataCollection;
        }
        return $schedule;
    }
}

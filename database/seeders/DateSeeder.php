<?php

namespace Database\Seeders;

use App\Models\Date;
use App\Services\Date\ScheduleService;
use Illuminate\Database\Seeder;

class DateSeeder extends Seeder
{
    public ScheduleService $scheduleService;
    public function __construct(){
        $this->scheduleService = new ScheduleService('2021-06-01','2021-09-01');
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Date::hasAny()==true){
            echo "Таблица уже имеет какие-то даты, проверьте таблицу dates,
                пока новые значения не были добавлены\n";
        }
        else {

            $this->scheduleService->tableGenerate();
            foreach ($this->scheduleService->datesTable as $date) {
                $date = Date::create([
                    'week_start'=>$date[0]
                ]);
                $date->save();
            }
        }
    }
}

<?php


namespace App\Services\Date;


use App\Models\Date;
use App\Models\House;
use DateTime;//

class ScheduleService{
    public DateTime $firstDate;
    public DateTime $lastDate;
    public $datesTable;

    /**
     * @throws \Exception
     */
    public function __construct (string $firstDate=null, string $lastDate){
        if ($firstDate == null)
            $this->firstDate = new DateTime(date('Y-m-d'));
        else
            $this->firstDate =  new DateTime($firstDate);
        $this->lastDate =  new DateTime($lastDate);
    }

    /**генерирует данные для таблицы дат в БД
     * учитывая год и неполные крайние недели
     * @throws \Exception
     */
    public function tableGenerate(){
        //вычисляем конец недели для $firstDate
        $endOfTheWeek = new DateTime($this->getFirstDate());
        $startOfTheWeek = null;

        $endOfTheWeek->modify('+'.(7-$this->firstDate->format('N')).' day');
        $this->datesTable[] = array($this->getFirstDate(),$endOfTheWeek->format('Y-m-d'));
        $startOfTheWeek = new DateTime($endOfTheWeek->modify('+1 day')->format('Y-m-d'));
        $endOfTheWeek->modify('+ 6 day');
        while(strtotime($this->getLastDate()) >
            strtotime($endOfTheWeek->format('Y-m-d'))){//пока не дошли до крайней даты

            $this->datesTable[] = array($startOfTheWeek->format('Y-m-d'),$endOfTheWeek->format('Y-m-d'));

            $startOfTheWeek = new DateTime($endOfTheWeek->modify('+1 day')->format('Y-m-d'));
            $endOfTheWeek->modify('+ 6 day');
        }
        $this->datesTable[] = array($startOfTheWeek->format('Y-m-d'),$this->getLastDate());
    }
    public function getFirstDate():string{
        return $this->firstDate->format('Y-m-d');
    }
    public function getLastDate():string{
        return $this->lastDate->format('Y-m-d');
    }

    /**
     * проверяет две даты даты въезда и выезда
     * пересечение Расписаний
     * @param $dateInFirst
     * @param $dateOutFirst
     * @param $dateInSecond
     * @param $dateOutSecond
     * @return bool
     */
    public static function isSchedulesOverlap($dateInFirst, $dateOutFirst, $dateInSecond, $dateOutSecond):bool{
        if (($dateInFirst < $dateInSecond || $dateInFirst >= $dateOutSecond) &&
            ($dateOutFirst <= $dateInSecond || $dateOutFirst > $dateOutSecond)) return false;//не пересекаются
        return true;//пересекаются
    }

    /**
     * собирает рассписание для всех домиков по дням начиная с текущей даты
     * и заканчивая крайней датой DateService::max
     * в ассоциативный массив передаются значения формата: arr[house->name] => [column_count,house->id,price,date]
     */

    public static function getScheduleByDay(){
        $houses = House::all();
        $schedule=[];
        $dates = DateService::getDateByDays(null,null,true);
        foreach($houses as $house){
            //если сегодняшний день суббота, то разьединить все слияния на данную датуа
            DateService::daysDivision($house,$dates[0]);
            foreach ($dates as $date){
                $dateTime = new DateTime($date);
                $priceByDate = $house->getPriceByDate($date);

                if ($dateTime->format('D') == 'Sat' && $priceByDate->weekend_price != 0) continue;


                    //если это пятница и weekend_price есть, то присвоить цену
                if($dateTime->format('D') == 'Fri'&& $priceByDate->weekend_price != 0 &&
                    $house->isDayBusy($dateTime->format('Y-m-d')) != 0){
                    $schedule[$house->name][] = [1,$house->id,'занято',$date];
                    $schedule[$house->name][] = [1,$house->id,'занято',$date];
                }

                else if ($dateTime->format('D') == 'Fri'&& $priceByDate->weekend_price != 0)//
                    $schedule[$house->name][] = [2,$house->id,$priceByDate->weekend_price.' руб',$date];//
                else if ($house->isDayBusy($date) == 0)
                    $schedule[$house->name][] = [1,$house->id,$priceByDate->weekday_price.' руб',$date];
                else $schedule[$house->name][] = [1,$house->id,'занято',$date];
            }
        }

        return $schedule;
    }

}



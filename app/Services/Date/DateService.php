<?php


namespace App\Services\Date;

use App\Models\Date;
use App\Models\House;
use DateTime;

class DateService
{
    public static $currentTimeZone = 'Europe/Moscow';
    public static string $min;
    public static string $max = "2021-08-31";

    public static array $russianDayOfWeek = ['Понедельник',
        'Вторник','Среда', 'Четверг','Пятница',
        'Суббота','Воскресенье'];

    public static array $russianMonths = ['Января','Февраля','Марта','Апреля','Мая',
        'Июня','Июля','Августа','Сентября','Октября','Ноября','Декабря'];

    /**
     * Возвращает диапазон дат с учетом возможного позднего прибытия,
     * если $laterArrival==true, то вчерашний день тоже считается если текущий по времени
     * от 0 до 3 часов утра есть окно
     * @param bool $laterArrival
     * @return array
     * @throws \Exception
     */
    public static function getDateRange(bool $laterArrival=false)
    {
        date_default_timezone_set(self::$currentTimeZone);
        $currentDate = new DateTime(date('Y-m-d'));
        if ($laterArrival == true){
            self::dateRollback($currentDate);
        }
        return ["min" =>$currentDate->format('Y-m-d'), "max" => self::$max];
    }

    /**
     * Изменят дату под позднее прибытие на 1 день до 3 часов ночи
     * @param DateTime $date
     */
    public static function dateRollback(DateTime $date){
        date_default_timezone_set(self::$currentTimeZone);
        $date->modify('+'.date('H').' hours');
        if ($date->format('G')>=0 && $date->format('G') < 3)
            $date->modify('-1 day');
    }

    /**
     * @return array
     * @throws \Exception
     * Перебор всех дат до max, начиная с сегодня и преобразование в DateUnit
     * Используется для формирования верхних строчек таблицы form
     */
    public static function getDateByDaysAsDateUnit():array{
        date_default_timezone_set(self::$currentTimeZone);
        $currentDate = new DateTime(date('Y-m-d'));
        self::dateRollback($currentDate);

        $dates = [];
        while(strtotime(self::$max) >= strtotime($currentDate->format('Y-m-d'))){
            $dateUnit = new DateUnit($currentDate->format('j'),
                self::$russianMonths[$currentDate->format('n')-1],
                self::$russianDayOfWeek[$currentDate->format('N')-1]);
            $dates[] = $dateUnit;
            $currentDate->modify('+1 day');
        }

        return $dates;
    }

    /**
     * Возвращает день недели указанной даты
     * @param string|DateTime $date - дата, день недели которой нужно узнать
     * @return mixed|string - день недели
     * @throws \Exception
     */
    public static function getDayOfWeek(string|DateTime $date)
    {
        if(gettype($date) == "string")
            $date = new DateTime($date);
        return self::$russianDayOfWeek[$date->format('N') - 1];
    }

    /**
     * Возвращает все дни в указанном диапазоне дат, либо от сегодня и до конца лета по умолчанию
     * Возвращяет так же вчерашнюю дату если сегодняшняя от 00:00 до 03:00 утра
     * @param string|null $fromDate - дата начала диапазона
     * @param string|null $toDate - дата конца диапазона
     * @param bool $lateArrival - считается ли позднее прибытие если да, то с 0-3 ночи добавить прошлую дату
     * @return array - все дни в указанном диапазоне в формате Y-m-d
     * @throws \Exception
     */
    public static function getDateByDays(string $fromDate = null, string $toDate = null,bool $lateArrival = false):array{
        date_default_timezone_set(self::$currentTimeZone);
        $dates = [];
        $currentDate = isset($fromDate) ? new DateTime($fromDate) : new DateTime(date('Y-m-d'));
        if(!isset($toDate))
            $toDate = self::$max;

        if ($lateArrival == true){
            self::dateRollback($currentDate);
        }

        while(strtotime($toDate) >= strtotime($currentDate->format('Y-m-d'))){
            $date = $currentDate->format('Y-m-d');
            $dates[] = $date;
            $currentDate->modify('+1 day');
        }

        return $dates;
    }

    /**
     * Преобразует дату в формат <день-месяц-день_недели>
     * @param $date дата для преобразования (принимает только объект DateTime)
     * @return DateUnit - возвращает дату в корректном формате в виде объекта DateUnit
     */
    public static function getDate(DateTime|string $date): DateUnit
    {
        if(gettype($date) == "string")
            $date = new DateTime($date);
        return new DateUnit($date->format('j'),
            self::$russianMonths[$date->format('n') - 1],
            self::$russianDayOfWeek[$date->format('N') - 1]);
    }

    /**
     * Преобразует массив строк с указанными датами или массив DateTime в массив DateUnit
     * @param array $dates - массив строк/массив DateTime
     * @return array - массив DateUnit
     * @throws \Exception
     */
    public static function getDates(array $dates) : array
    {
        if(gettype($dates) != "array")
            return [];
        $datesAsDateUnit = [];
        foreach($dates as $date)
        {
            if(gettype($date) == "string")
                $date = new DateTime($date);
            $datesAsDateUnit[] = new DateUnit($date->format('j'),
                self::$russianMonths[$date->format('n') - 1],
                self::$russianDayOfWeek[$date->format('N') - 1]);
        }

        return $datesAsDateUnit;
    }

    /**
     * Разделяем пят-субботу, для того, что бы продать хотя бы субботу
     * если какая-либо дата не куплена
     * @param House $house
     * @param string $date
     * @throws \Exception
     */
    public static function daysDivision(House $house, string $date){
        if (DateService::getDayOfWeek($date) == "Суббота"){
            $house->updatePriceByDate([['price'=>"0",'week_part'=>'weekend_price'],
                ['price'=>null,'week_part'=>'weekday_price']],Date::getId($date));
        }
    }

    /**
     * собирает рассписание для всех домиков по дням начиная с текущей даты
     * и заканчивая крайней датой DateService::max
     * в ассоциативный массив передаются значения формата: $schedule[house->name] => [column_count,house->id,price,date]
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
                    self::isDayBusy($house,$dateTime->format('Y-m-d')) != 0){
                    $schedule[$house->name][] = [1,$house->id,'занято',$date];
                    $schedule[$house->name][] = [1,$house->id,'занято',$date];
                }

                else if ($dateTime->format('D') == 'Fri'&& $priceByDate->weekend_price != 0)//
                    $schedule[$house->name][] = [2,$house->id,$priceByDate->weekend_price.' руб',$date];//
                else if (self::isDayBusy($house,$date) == 0)
                    $schedule[$house->name][] = [1,$house->id,$priceByDate->weekday_price.' руб',$date];
                else $schedule[$house->name][] = [1,$house->id,'занято',$date];
            }
        }

        return $schedule;
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
    public static function isDayBusy(House $house,$date){

        return count($house->orders()->whereDate('date_in','<=',$date)->whereDate('date_out','>',$date)->get());
    }
}

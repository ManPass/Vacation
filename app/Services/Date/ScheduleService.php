<?php


namespace App\Services\Date;

use DateTime;

/**
 * Class ScheduleService
 * @package App\Services\Date
 * Генерирует рассписание по неделям от диапазона дат
 * или с текущей даты до введенной, учитывает не полные недели
 * формат таблицы дат $dateTable[X]=[startWeek(Y-m-d),endWeek(Y-m-d)]
 */
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

}



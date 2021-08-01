<?php


namespace App\Services\Date;


class DateUnit
{
    public int $dayOfMonth;
    public string $month;
    public string $dayOfWeek;
    public function __construct(int $dayOfMonth, string $month, string $dayOfWeek){
        $this->dayOfMonth = $dayOfMonth;
        $this->month = $month;
        $this->dayOfWeek = $dayOfWeek;
    }

}

<?php


namespace App\Services;

use App\Models\House;
use App\Services\Date\DateService;
use DateTime;

class OrderService
{
    /**
     * Возвращает массив для передачи данных на форму, в который входит результат проверки на корректность дат и
     * полная стоимость заказа за все дни. В случае, если проверка дат вернула false, то стоимость заказа не высчитывается
     * @param House $house - модель дома, в который собираются заселиться
     * @param string|\DateTime $dateIn - дата въезда
     * @param string|\DateTime $dateOut - дата выезда
     * @return array - массив для передачи данных на форму
     * @throws \Exception
     */
    public static function checkOrderState(House $house, string|DateTime $dateIn, string|DateTime $dateOut) : array
    {
        $orderState = [];
        $orderState["check"] = self::isDateRangeCorrect($house, $dateIn, $dateOut);
        if(!$orderState["check"])
            return $orderState;
        $orderState["fullPrice"] = self::getFullPrice($house, $dateIn, $dateOut);
        return $orderState;
    }

    /**
     * Проверка на корректность выбранного диапазона дат, проверка с учетом слияния дат
     * @param House $house - модель дома
     * @param string $dateIn - дата въезда
     * @param string $dateOut - дата выезда
     * @return bool - true, если диапазон корректен
     * @throws \Exception
     */
    private static function isDateRangeCorrect(House $house, string $dateIn, string $dateOut) : bool
    {
        //Если пят-суббота склеяны ты не можешь взять заказ начиная с субботы или выехать в субботу
        if(DateService::getDayOfWeek($dateIn) == "Суббота" &&  $house->getPriceByDate($dateIn)['weekend_price'] != 0)
            return false;
        if(DateService::getDayOfWeek($dateOut) == "Суббота" &&  $house->getPriceByDate($dateOut)['weekend_price'] != 0)
            return false;
        //если дата выезда пятница и есть слияния недель, то ошибка потому что суббота тоже должна быть в заказе
        if (DateService::getDayOfWeek($dateOut) == "Пятница" && $house->getPriceByDate($dateOut)['weekend_price'] != 0)
            return false;

        return true;
    }

    /**
     * Получить полную цену за заказ не учитывая дату выезда
     * Учитывая слияния пятнц-субботы
     * @param House $house
     * @param string $dateIn
     * @param string $dateOut
     * @return int|mixed
     * @throws \Exception
     */
    private static function getFullPrice(House $house, string $dateIn, string $dateOut)
    {
        //день выезда в оплату не входит(не включительно!)
        $dateOut = new DateTime($dateOut);
        $dateOut->modify('-1 day');
        $dateOut = $dateOut->format('Y-m-d');

        $dates = DateService::getDateByDays($dateIn, $dateOut);

        $fullPrice = 0;
        foreach($dates as $date)
        {
            $dayOfWeek = DateService::getDayOfWeek($date);
            if ($dayOfWeek == "Суббота" && $house->getPriceByDate($date)['weekend_price'] != 0) continue;
            if($dayOfWeek != "Пятница")
            {
                $fullPrice += $house->getPriceByDate($date)["weekday_price"];
            }
            else if($house->getPriceByDate($date)['weekend_price'] != 0)
            {
                $fullPrice += $house->getPriceByDate($date)["weekend_price"];
            }

        }

        return $fullPrice;
    }
}

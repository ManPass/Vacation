<?php

namespace App\Models;

use App\Services\Date\DateService;
use App\Services\Date\DateUnit;
use App\Services\Date\ScheduleService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;

use DateTime;

class House extends Model
{
    public $timestamps = false;
    protected $fillable = ['name', 'description', 'beds_count',
                            'has_electricity', 'has_shower'];
    use HasFactory;

    function dates(): BelongsToMany
    {
        return $this->belongsToMany(Date::class, 'house_dates',
            'house_id', 'date_id');
    }

    function orders()
    {
        return $this->hasMany('App\Models\Order');
    }

    function photos()
    {
        return $this->hasMany('App\Models\Photo');
    }

    /**
     * Возвращает коллекцию объектов House или пустой массив
     * @return array|Collection
     */
    static function getAll() : array|Collection
    {
        return House::all() ?? [];
    }

    /**
     * Возвращает все названия домиков или пустой массив
     * @return Collection
     */
    static function getNames()
    {
        return self::select('name', 'id')->get();
    }

    /**
     * Свободен ли домик в указанную дату въезда
     * @param $date - дата въезда
     * @return bool - возвращает true, если домик свободен
     */
    public function isFreeOn($dateInOrder,$dateOutOrder): bool
    {
        $busyDays = $this->getBusyDaysAsQuery();
        foreach($busyDays as $range) {
            $dateIn = $range['date_in'];
            $dateOut = $range['date_out'];
            if (ScheduleService::isSchedulesOverlap($dateInOrder,$dateOutOrder,$dateIn,$dateOut) ||
                ScheduleService::isSchedulesOverlap($dateIn,$dateOut,$dateInOrder,$dateOutOrder)) return false;
        }
        return true;
    }

    /**
     * Возвращает даты, в которые занят дом, в формате массива DateUnit
     * важно для удобного и понятного форматирования в blade
     * @return array - массив дат
     */
    public function getBusyDaysAsDateUnit(): array
    {
        $busyDaysFormat = [];
        $busyDaysQuery = $this->getBusyDaysAsQuery();

        foreach($busyDaysQuery as $range)
        {
            $dateIn = new DateTime($range['date_in']);
            $dateOut = new DateTime($range['date_out']);
            //$busyDaysFormat[] = [DateService::getDate($dateIn),DateService::getDate($dateOut)];
            $busyDaysFormat["start"][] = DateService::getDate($dateIn);
            $busyDaysFormat["end"][] = DateService::getDate($dateOut);
        }
        return $busyDaysFormat;
    }

    /**
     * Возвращает массив голых дат для последующей обработки
     * в следствии используется для генерации формы
     */
    public function getBusyDays(){
        $busyDaysQuery = $this->getBusyDaysAsQuery();
        $busyDays=[];
        foreach ($busyDaysQuery as $range){
            $busyDays[] = array($range['date_in'],$range['date_out']);
        }
        return $busyDays;
    }

    private function getBusyDaysAsQuery(): Collection
    {
        return $this->orders()->select('date_in', 'date_out')->get();
    }
    //Вынести в DateService
    public function isDayBusy($date){

        return count($this->orders()->whereDate('date_in','<=',$date)->whereDate('date_out','>',$date)->get());
    }

    /**
     * нахождение цены по заданной дате для домика
     * @param $date
     * @return Collection
     * @throws \Exception
     */
    //Вынести в DateService
    public function getPriceByDate($date){
        //привести любую $date к понедельнику и найти через запрос цену
        $date = new DateTime($date);
        $weekStart = $date->modify('-'.($date->format('N')-1).' day')->format('Y-m-d');
        return $this->dates()->where('week_start','=',$weekStart)->select('weekday_price','weekend_price')->get()->first();
    }
    //Вынести в DateService
    public function isDayInDiapason($date)
    {
        return count($this->orders()->whereDate('date_in', '<=', $date)->whereDate('date_out', '>', $date)->get());
    }

    /**
     * Обновить цену за определенную дату для самого домика
     */
    public function updatePriceByDate($prices,$date_id){
        foreach($prices as $price){
            if ($price['price'] != null) {
                $this->dates()->updateExistingPivot($date_id, [$price['week_part'] => $price['price']]);
            }
        }
    }

    /**
     * Возвращает минимальную цену за дом
     * @return mixed
     */
    public function getMinimalPrice()
    {
        $minWeekdayPrice = $this->dates()->min('weekday_price');
        $minWeekendPrice = $this->dates()->min('weekend_price');

        return $minWeekdayPrice > $minWeekendPrice ? $minWeekendPrice : $minWeekdayPrice;
    }

    /**
     * Возвращает первое привязанное фото или пустую строку
     * @return mixed|string
     */
    function getPhoto()
    {
        $photos = "";
        if($this->photos()->first() != null)
            $photos = $this->photos()->first()["photo"];
        return $photos;
    }

    /**
     * Возвращает цену за дом за неделю, в которую входит указанный день
     * @param $weekStart
     * @return int|mixed
     */
    function getPrices($weekStart)
    {
        return $this->dates()->where('week_start', '=', $weekStart)->select('weekday_price', 'weekend_price')
            ->get()->first() ?? 0;
    }

}

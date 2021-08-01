<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTime;
class Date extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['week_start'];
    protected $dateFormat = 'dd.mm.yyyy';

    function houses()
    {
        return $this->belongsToMany(House::class, 'house_dates', 'date_id', 'house_id');
    }
    static function hasAny():bool{
        if (count(Date::all()) == 0) return false;
        return true;
    }

    static function getAll(): Collection|array
    {
        return Date::all() ?? [];
    }
    /**
     * Возвращает все даты связанные с домиком, а так же цены
     */
    //Вынести в Date модель
    public static function getDatesWithPrices(House $house){
        $currentWeekStart = new DateTime(date('Y-m-d'));
        $currentWeekStart->modify('-'.($currentWeekStart->format('N')-1).' day');

        return $house->dates()->select('date_id','weekday_price','weekend_price','week_start')->whereDate('week_start','>=',$currentWeekStart->format('Y-m-d'))->get();
    }

    /**
     * Возвращает ID даты по любой дате входящей в диапазон недели
     * поскольку в таблице хранятся только даты начала недель, то
     * дата приводится к понедельнику и происходит поиск
     * @param $date
     * @return mixed
     * @throws \Exception
     */
    public static function getId($date){
        $date = new DateTime($date);
        $date->modify("-".($date->format('N')-1)."day");
        return Date::where('week_start',$date->format('Y-m-d'))->first()['id'];
    }

    public function getWeek()
    {
        $weekStartDateTime = new DateTime($this->week_start);
        $weekStart = $weekStartDateTime->format('m.d');
        $weekEnd = $weekStartDateTime->modify('+6 day')->format('m.d');

        return ['start' => $weekStart, 'end' => $weekEnd];
    }

}

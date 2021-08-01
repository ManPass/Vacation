<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    public static string $currency = 'руб';

    protected $fillable = ['house_id', 'name', 'email', 'phone_number', 'date_in',
        'date_out'];

    public $timestamps = false;
    protected $dateFormat = 'dd.mm.yyyy';


    function house()
    {
        $this->belongsTo(House::class, 'house_id', 'id');
    }
}

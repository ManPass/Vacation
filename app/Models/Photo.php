<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $fillable = ['house_id', 'photo'];
    public $timestamps = true;

    public function houses()
    {
        return $this->belongsTo(House::class, 'house_id', 'id');
    }
}

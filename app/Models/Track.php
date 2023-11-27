<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Track extends Model
{
    use HasFactory;

    protected $fillable = [
        'imei',
        'latitude',
        'longitude',
        'angle',
        'altitude',
        'satellites',
        'speed',
        'battery',
        'datetime'
    ];
}

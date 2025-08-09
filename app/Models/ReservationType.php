<?php

namespace App\Models;

use App\Casts\CustomString;
use Illuminate\Database\Eloquent\Model;

class ReservationType extends Model {
    protected $fillable = [
        'display_name',
        'technical_name'
    ];

    protected $casts = [
        'display_name' => CustomString::class,
        'technical_name' => CustomString::class
    ];
}

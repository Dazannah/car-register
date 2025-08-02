<?php

namespace App\Models;

use App\Casts\CustomString;
use Illuminate\Database\Eloquent\Model;

class Status extends Model {

    protected $fillable = [
        'dipslay_name',
        'technical_name'
    ];

    protected $casts = [
        'dipslay_name' => CustomString::class,
        'technical_name' => CustomString::class
    ];
}

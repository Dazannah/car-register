<?php

namespace App\Models;

use App\Casts\CustomPrimaryKey;
use App\Casts\CustomString;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model {
    protected $fillable = [
        'licence_plate',
        'type',
        'vin',
        'status_id'
    ];

    protected $casts = [
        'licence_plate' => CustomString::class,
        'type' => CustomString::class,
        'vin' => CustomString::class,
        'status_id' => CustomPrimaryKey::class
    ];

    public function users() {
        return $this->belongsToMany(User::class);
    }
}

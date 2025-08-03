<?php

namespace App\Models;

use App\Casts\CustomPrimaryKey;
use App\Casts\CustomString;
use App\Casts\LicencePlate;
use App\Casts\Vin;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model {
    protected $fillable = [
        'licence_plate',
        'type',
        'vin',
        'status_id'
    ];

    protected $casts = [
        'licence_plate' => LicencePlate::class,
        'type' => CustomString::class,
        'vin' => Vin::class,
        'status_id' => CustomPrimaryKey::class
    ];

    public function users() {
        return $this->belongsToMany(User::class);
    }

    public function status() {
        return $this->belongsTo(Status::class);
    }
}

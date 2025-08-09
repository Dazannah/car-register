<?php

namespace App\Models;

use App\Casts\CustomBoolean;
use App\Casts\CustomPrimaryKey;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model {
    const CREATED_AT = 'pickup_at';
    const UPDATED_AT = 'return_at';

    protected $fillable = [
        'user_id',
        'vehicle_id',
        'reservation_type_id',
        'pickup_at',
        'return_at',
        'is_closed'
    ];

    protected $casts = [
        'user_id' => CustomPrimaryKey::class,
        'vehicle_id' => CustomPrimaryKey::class,
        'reservation_type_id' => CustomPrimaryKey::class,
        'is_closed' => CustomBoolean::class
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function vehicle() {
        return $this->belongsTo(Vehicle::class);
    }

    public function reservationType() {
        return $this->belongsTo(ReservationType::class);
    }
}

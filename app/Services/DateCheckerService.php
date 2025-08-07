<?php

namespace App\Services;

use App\Models\Trip;
use App\Interfaces\IDateChecker;
use App\Models\User;
use Carbon\Carbon;

class DateCheckerService implements IDateChecker {

  public function check_if_time_available($time, $vehicle_id): Trip|null {
    //kisebb vagy ugyan annyi mint a jelen időpont, és van folyamatban lévő pickup ami kisebb kezdete mint a start time
    if ($colliding_trip = $this->check_if_collide_with_pickup($time, $vehicle_id))
      return $colliding_trip;

    //nagyobb mint a jelen időpont, és van olyan trip, aminek kisebbvagy ugyan annyi a kezdés és nagyobb befejezés
    if ($colliding_trip = $this->check_if_collide_with_prereserv($time, $vehicle_id))
      return  $colliding_trip;

    // Trip::with(['user', 'vehicle'])->where();
    return null;
  }

  public function check_if_collide_with_pickup($time, $vehicle_id): Trip|null {
    //check_if_smaller_than_current_and_is_reserve_in_progress
    $current_time = Carbon::now('UTC');

    return Trip::with(['user', 'vehicle'])->where('is_closed', '=', false)->where('vehicle_id', '=', $vehicle_id)->where('pickup_at', '>=',  $current_time)->first();
  }

  public function check_if_collide_with_prereserv($time, $vehicle_id): Trip|null {
    $current_time = Carbon::now('UTC');

    return Trip::with(['user', 'vehicle'])->where('is_closed', '=', false)->where('vehicle_id', '=', $vehicle_id)->where('return_at', '>',  $current_time)->first();
  }
}

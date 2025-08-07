<?php

namespace App\Interfaces;

use App\Models\Trip;

interface IDateChecker {
    public function check_if_time_available($start_time, $vehicle_id): Trip|null;
    public function check_if_collide_with_pickup($time, $vehicle_id): Trip|null;
    public function check_if_collide_with_prereserv($time, $vehicle_id): Trip|null;
}

<?php

namespace App\Interfaces;

interface IDateChecker {
    public function check_if_start_available($start_time, $vehicle_id): bool;
    public function check_if_end_available($end_time, $vehicle_id): bool;
}

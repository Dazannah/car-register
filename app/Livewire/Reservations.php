<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Trip;
use App\Models\Vehicle;
use Livewire\Component;
use App\Interfaces\IDateChecker;
use Illuminate\Database\Eloquent\Collection;

class Reservations extends Component {
    public Collection $user_vehicles;

    public bool $is_booking_time_available = false;

    public $booking_start;
    public bool $is_booking_start_available = false;
    public Trip|null $interfere_with_start;

    public $booking_end;
    public bool $is_booking_end_available = false;
    public Trip|null $interfere_with_end;

    public int|null $booking_vehicle_id;
    public Trip|null $interfering_trip;
    private IDateChecker $date_checker_service;

    public string|null $success_message;
    public string|null $error_message;

    public function mount() {
        $this->get_user_vehicles();

        if (count($this->user_vehicles) > 0)
            $this->booking_vehicle_id = $this->user_vehicles[0]->id;

        $this->check_if_booking_booking_start_available();
        $this->check_if_booking_booking_end_available();
        $this->check_if_booking_time_available();
    }

    public function boot(IDateChecker $date_checker_service) {
        $this->get_user_vehicles();

        $this->date_checker_service = $date_checker_service;
        $this->error_message = null;
        $this->success_message = null;
        $this->interfering_trip = null;

        $this->check_if_booking_booking_start_available();
        $this->check_if_booking_booking_end_available();
        $this->check_if_booking_time_available();
    }

    public function get_user_vehicles() {
        $this->user_vehicles = Vehicle::with([
            'users',
            'trips' =>
            function ($query) {
                $query->where('is_closed', false);
            }
        ])->whereHas(
            'users',
            function ($query) {
                return $query->where('id', auth()->user()->id);
            }
        )->get();
    }

    private function check_if_booking_booking_start_available() {
        if (Carbon::parse($this->booking_start) <= Carbon::now()) {
            $this->interfere_with_start = null;
            $this->is_booking_start_available = false;
            return;
        }

        if (!empty($this->booking_start) && !empty($this->booking_vehicle_id)) {
            if ($trip = $this->date_checker_service->check_if_time_available($this->booking_end, $this->booking_vehicle_id)) {
                $this->interfere_with_start = $trip;
                $this->is_booking_start_available = false;
            } else {
                $this->interfere_with_start = null;
                $this->is_booking_start_available = true;
            }
        }
    }

    private function check_if_booking_booking_end_available() {
        if (Carbon::parse($this->booking_end) <= Carbon::now()) {
            $this->interfere_with_end = null;
            $this->is_booking_end_available = false;
            return;
        }

        if (!empty($this->booking_end) && !empty($this->booking_vehicle_id))
            if ($trip = $this->date_checker_service->check_if_time_available($this->booking_start, $this->booking_vehicle_id)) {
                $this->interfere_with_end = $trip;
                $this->is_booking_end_available = false;
            } else {
                $this->interfere_with_end = null;
                $this->is_booking_end_available = true;
            }
    }


    private function check_if_booking_time_available() {
        if (Carbon::parse($this->booking_start) < Carbon::parse($this->booking_end))
            $this->is_booking_time_available = $this->is_booking_start_available && $this->is_booking_end_available;
        else
            $this->is_booking_time_available = false;
    }

    public function render() {
        return view('livewire.reservations');
    }
}

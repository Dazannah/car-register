<?php

namespace App\Livewire;

use App\Interfaces\IDateChecker;
use Exception;
use DateTimeZone;
use Carbon\Carbon;
use App\Models\Trip;
use App\Models\Vehicle;
use Livewire\Component;
use Illuminate\Database\Eloquent\Collection;

class Dashboard extends Component {
    public string $pickup_time;
    public Collection $user_vehicles;
    public Collection|null $trips_in_future;

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

    protected $listeners = ['pickup_vehicle', 'return_vehicle'];

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

        $this->pickup_time = Carbon::now('Europe/Budapest')->format('Y-m-d H:i');

        $this->trips_in_future = null;

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

    public function updated($property) {

        if ($property === 'booking_start' || $property === 'booking_vehicle_id') {
            $this->check_if_booking_booking_start_available();
        }

        if ($property === 'booking_end' || $property === 'booking_vehicle_id') {
            $this->check_if_booking_booking_end_available();
        }

        $this->check_if_booking_time_available();
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

    public function pickup_vehicle($vehicle_id) {
        try {
            $localDate = Carbon::parse($this->pickup_time, new DateTimeZone('Europe/Budapest'));
            $utcDate = $localDate->copy()->timezone('UTC');

            if ($trip = Trip::where([['vehicle_id', '=', $vehicle_id], ['is_closed', '=', false]])->with('user')->first())
                throw new Exception('Gépjárműt már lefoglalta: ' . $trip->user->name);

            if ($trip = Trip::where([['vehicle_id', '=', $vehicle_id], ['pickup_at', '<=', $utcDate], ['return_at', '>=', $utcDate]])->first())
                throw new Exception('A gépjármű ebben az időpontban nem volt elérhető');

            $trip = Trip::create([
                'user_id' => auth()->user()->id,
                'vehicle_id' => $vehicle_id,
                'pickup_at' => $utcDate
            ]);

            $trip->save();
            $this->mount();


            $this->success_message = 'Felvétel sikeres';
            $this->dispatch('show_success');
        } catch (Exception $err) {
            $this->error_message = $err->getMessage();

            $this->mount();
        }
    }

    public function return_vehicle($trip_id) {
        try {
            if ($trip = Trip::where([['id', '=', $trip_id], ['user_id', '=', auth()->user()->id]])->first()) {
                $trip->is_closed = true;

                $trip->save();
                $this->mount();

                $this->success_message = 'Leadás sikeres';
                $this->dispatch('show_success');
            } else {
                throw new Exception('A gépjárműt nem te vitted el.');
            }
        } catch (Exception $err) {
            $this->error_message = $err->getMessage();

            $this->mount();
        }
    }

    public function render() {
        return view('livewire.dashboard');
    }
}

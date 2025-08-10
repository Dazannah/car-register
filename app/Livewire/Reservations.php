<?php

namespace App\Livewire;

use DateTimeZone;
use Carbon\Carbon;
use App\Models\Trip;
use App\Models\Vehicle;
use Livewire\Component;
use App\Interfaces\IDateChecker;
use Illuminate\Database\Eloquent\Collection;

class Reservations extends Component {
    public Collection $user_vehicles;

    public bool $is_booking_time_available = false;
    public Trip|null $interfere_with_the_whole_time;

    public string|null $booking_start;
    public bool $is_booking_start_available = false;
    public Trip|null $interfere_with_start;

    public string|null $booking_end;
    public bool $is_booking_end_available = false;
    public Trip|null $interfere_with_end;

    public int|null $booking_vehicle_id;
    public Trip|null $interfering_trip;
    private IDateChecker $date_checker_service;

    public string|null $success_message;
    public string|null $error_message;

    protected $listeners = ['prebook_vehicle'];

    public function mount() {
        $this->get_user_vehicles();
    }

    public function boot(IDateChecker $date_checker_service) {
        $this->get_user_vehicles();

        $this->date_checker_service = $date_checker_service;
        $this->success_message = null;
        $this->error_message = null;
        $this->interfering_trip = null;
    }

    public function updated($property_name) {
        if ($property_name === 'booking_start' || $property_name === 'booking_end'  || $property_name === 'booking_vehicle_id') {
            $this->check_if_start_available();
            $this->check_if_end_available();
            $this->check_if_booking_time_available();
        }
    }

    public function check_if_start_available() {
        if (isset($this->booking_start)) {
            $utc_booking_start = Carbon::parse($this->booking_start, new DateTimeZone('Europe/Budapest'))->copy()->timezone('UTC');

            $this->interfere_with_start = Trip::with(['user'])->where([
                ['vehicle_id', '=', $this->booking_vehicle_id],
                ['pickup_at', '<=', $utc_booking_start],
                ['return_at', '>', $utc_booking_start]
            ])->first();

            if (isset($this->interfere_with_start))
                $this->reset(['is_booking_start_available', 'is_booking_time_available']);
            else
                $this->is_booking_start_available = true;
        }
    }

    public function check_if_end_available() {
        if (isset($this->booking_end)) {
            $utc_booking_end = Carbon::parse($this->booking_end, new DateTimeZone('Europe/Budapest'))->copy()->timezone('UTC');

            $this->interfere_with_end = Trip::with(['user'])->where([
                ['vehicle_id', '=', $this->booking_vehicle_id],
                ['pickup_at', '<=', $utc_booking_end],
                ['return_at', '>', $utc_booking_end]
            ])->first();

            if (isset($this->interfere_with_end))
                $this->reset(['is_booking_end_available', 'is_booking_time_available']);
            else
                $this->is_booking_end_available = true;
        }
    }

    public function check_if_booking_time_available() {
        if ($this->is_booking_start_available &&  $this->is_booking_end_available) {
            $utc_booking_start = Carbon::parse($this->booking_start, new DateTimeZone('Europe/Budapest'))->copy()->timezone('UTC');
            $utc_booking_end = Carbon::parse($this->booking_end, new DateTimeZone('Europe/Budapest'))->copy()->timezone('UTC');

            $this->interfere_with_the_whole_time = Trip::with(['user'])->where([
                ['vehicle_id', '=', $this->booking_vehicle_id],
                ['pickup_at', '>=', $utc_booking_start],
                ['return_at', '<=', $utc_booking_end]
            ])->first();

            if (isset($this->interfere_with_the_whole_time))
                $this->reset(['is_booking_time_available']);
            else
                $this->is_booking_time_available = !isset($this->interfere_with_start) &&  !isset($this->interfere_with_end);
        }
    }

    public function prebook_vehicle() {
        $this->check_if_start_available();
        $this->check_if_end_available();
        $this->check_if_booking_time_available();

        $utc_booking_start = Carbon::parse($this->booking_start, new DateTimeZone('Europe/Budapest'))->copy()->timezone('UTC')->format('Y-m-d H:i');
        $utc_booking_end = Carbon::parse($this->booking_end, new DateTimeZone('Europe/Budapest'))->copy()->timezone('UTC')->format('Y-m-d H:i');

        $trip = Trip::create([
            'user_id' => auth()->user()->id,
            'vehicle_id' => $this->booking_vehicle_id,
            'pickup_at' => $utc_booking_start,
            'return_at' => $utc_booking_end,
            'reservation_type_id' => 2
        ]);

        $trip->save();
        $this->mount();

        $this->reset([
            'booking_start',
            'is_booking_start_available',
            'interfere_with_start',
            'booking_end',
            'is_booking_end_available',
            'interfere_with_end',
            'booking_vehicle_id',
            'interfering_trip',
            'is_booking_time_available'
        ]);

        $this->success_message = 'Előfoglalás sikeres';
        $this->dispatch('show_success');
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

    public function render() {
        return view('livewire.reservations');
    }
}

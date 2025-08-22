<?php

namespace App\Livewire;

use Exception;
use DateTimeZone;
use Carbon\Carbon;
use App\Models\Trip;
use App\Models\Vehicle;
use Livewire\Component;
use App\Events\PickupVehicle;
use App\Events\ReturnVehicle;
use App\Interfaces\IDateChecker;
use Illuminate\Database\Eloquent\Collection;

class Dashboard extends Component {
    public string $pickup_time;
    public string $time_now_utc;
    public Collection $user_vehicles;

    public Collection $user_open_underway_trips;

    public int|null $booking_vehicle_id;

    protected $listeners = ['pickup_vehicle', 'return_vehicle', 'return_preregistered_vehicle'];

    public string|null $success_message;
    public string|null $error_message;

    public function mount() {
        $this->get_user_vehicles();
        $this->time_now_utc =  Carbon::now('UTC');

        if (count($this->user_vehicles) > 0)
            $this->booking_vehicle_id = $this->user_vehicles[0]->id;

        $this->get_open_underway_trips();
    }

    public function boot() {
        $this->pickup_time = Carbon::now('Europe/Budapest')->format('Y-m-d H:i');

        $this->error_message = null;
        $this->success_message = null;
    }

    public function get_user_vehicles() {
        $now = Carbon::now(new DateTimeZone('UTC'))->format('Y-m-d H:i');

        $this->user_vehicles = Vehicle::with([
            'users',
            'trips' =>
            function ($query) use ($now) {
                $query->where('is_closed', false)->where([['pickup_at', '<=', $now], ['return_at', '>=', $now]]);
            }
        ])->whereHas(
            'users',
            function ($query) {
                return $query->where('id', auth()->user()->id);
            }
        )->get();
    }

    public function get_open_underway_trips() {
        $this->user_open_underway_trips = Trip::with(['vehicle'])->where([['user_id', '=', auth()->user()->id], ['is_closed', false], ['pickup_at', '<=', $this->time_now_utc], ['reservation_type_id', '=', 2]])->get();
    }

    public function pickup_vehicle($vehicle_id) {
        try {
            $localDate = Carbon::parse($this->pickup_time, new DateTimeZone('Europe/Budapest'));
            $utcDate = $localDate->copy()->timezone('UTC')->format('Y-m-d H:i');

            if ($trip = Trip::where([['vehicle_id', '=', $vehicle_id], ['pickup_at', '<', $this->time_now_utc], ['return_at', '>=', $this->time_now_utc]])->with('user')->first())
                throw new Exception('Gépjárműt már lefoglalta: ' . $trip->user->name);

            if ($trip = Trip::where([['vehicle_id', '=', $vehicle_id], ['pickup_at', '<=', $utcDate], ['return_at', '>=', $utcDate], ['is_closed', '=', false]])->first())
                throw new Exception('A gépjármű ebben az időpontban nem volt elérhető');

            $return_at = $localDate->copy()->endOfDay()->timezone('UTC');

            $trip = Trip::create([
                'user_id' => auth()->user()->id,
                'vehicle_id' => $vehicle_id,
                'pickup_at' => $utcDate,
                'return_at' => $return_at
            ]);

            $trip->save();
            $this->mount();

            event(new PickupVehicle($trip));

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

                $trip->return_at = Carbon::now(new DateTimeZone('UTC'))->format('Y-m-d H:i');
                $trip->save();
                $this->mount();

                event(new ReturnVehicle($trip));

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

    public function return_preregistered_vehicle($trip_id) {
        try {
            if ($trip = Trip::where([['id', '=', $trip_id], ['user_id', '=', auth()->user()->id]])->first()) {
                $trip->is_closed = true;
                $trip->timestamps = false;

                $now = Carbon::now(new DateTimeZone('UTC'))->format('Y-m-d H:i');

                $trip->return_at = $trip->return_at > $now ? $now : $trip->return_at;
                $trip->save();
                $this->mount();

                event(new ReturnVehicle($trip));

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

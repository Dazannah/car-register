<?php

namespace App\Livewire;

use App\Models\Trip;
use App\Models\Vehicle;
use Exception;
use Livewire\Component;
use Illuminate\Database\Eloquent\Collection;

class Dashboard extends Component {
    public string $pickup_time;
    public Collection $user_vehicles;

    protected $listeners = ['pickup_vehicle', 'return_vehicle'];

    public string|null $success_message;
    public string|null $error_message;

    public function boot() {
        $this->error_message = null;
        $this->success_message = null;
    }

    public function pickup_vehicle($vehicle_id) {
        try {
            if ($trip = Trip::where([['vehicle_id', '=', $vehicle_id], ['is_closed', '=', false]])->with('user')->first())
                throw new Exception('Gépjárműt már lefoglalta: ' . $trip->user->name);
            $trip = Trip::create([
                'user_id' => auth()->user()->id,
                'vehicle_id' => $vehicle_id,
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
                throw new Exception('Gépjárműt nem te vitted el.');
            }
        } catch (Exception $err) {
            $this->error_message = $err->getMessage();

            $this->mount();
        }
    }

    public function mount() {
        date_default_timezone_set("Europe/Budapest");
        $this->pickup_time = date("Y-m-d H:i");
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
        return view('livewire.dashboard');
    }
}

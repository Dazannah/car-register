<?php

namespace App\Livewire;

use App\Models\Vehicle;
use Livewire\Component;
use Illuminate\Database\Eloquent\Collection;

class Dashboard extends Component {
    public string $pickup_time;
    public Collection $user_vehicles;

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

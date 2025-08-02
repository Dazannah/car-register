<?php

namespace App\Livewire;

use Livewire\Component;

class Dashboard extends Component {
    public string $pickup_time;

    public function mount() {
        date_default_timezone_set("Europe/Budapest");
        $this->pickup_time = date("Y-m-d H:i");
    }

    public function render() {
        return view('livewire.dashboard');
    }
}

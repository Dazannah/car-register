<?php

namespace App\Livewire\Admin;

use App\Models\Status;
use App\Models\Vehicle;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

class Vehicles extends Component {
    use WithPagination;

    #[URL(as: 'licance_plate')]
    public string $filter_licence_plate;
    #[URL(as: 'type')]
    public string $filter_type;
    #[URL(as: 'vin')]
    public string $filter_vin;
    #[URL(as: 'status')]
    public string $filter_status;

    public function mount() {
    }

    protected $listeners = ['remount_vehicles', 'reset_vehicles_filter'];

    public function remount_vehicles() {
        $this->mount();
    }

    public function reset_vehicles_filter() {
        $this->reset('filter_licence_plate', 'filter_type', 'filter_vin', 'filter_status');
        $this->resetPage();
        $this->dispatch('remount_vehicles');
    }

    public function filter_vehicles() {
        return Vehicle::with(['status'])->when(
            isset($this->filter_licence_plate) && !empty($this->filter_licence_plate),
            function ($query) {
                return $query->where('licence_plate', 'LIKE', "%$this->filter_licence_plate%");
            }
        )->when(
            isset($this->filter_type) && !empty($this->filter_type),
            function ($query) {
                return $query->where('type', 'LIKE', "%$this->filter_type%");
            }
        )->when(
            isset($this->filter_vin) && !empty($this->filter_vin),
            function ($query) {
                return $query->where('licence_plate', 'LIKE', "%$this->filter_vin%");
            }
        )->when(
            isset($this->filter_status) && !empty($this->filter_status),
            function ($query) {
                return $query->whereHas('status', function ($inside_query) {
                    return $inside_query->where('technical_name', '=', "$this->filter_status");
                });
            }
        )->paginate(10);
    }

    public function statuses() {
        return Status::all();
    }

    public function render() {
        return view('livewire.admin.vehicles', [
            'vehicles' => $this->filter_vehicles(),
            'statuses' => $this->statuses()
        ]);
    }
}

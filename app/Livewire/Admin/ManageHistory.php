<?php

namespace App\Livewire\Admin;

use App\Models\Trip;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use App\Models\ReservationType;
use Illuminate\Database\Eloquent\Collection;

class ManageHistory extends Component {
    use WithPagination;

    public Collection $reservation_types;

    #[URL(as: 'name')]
    public string $filter_name;
    #[URL(as: 'pickup_at')]
    public string $filter_pickup_at;
    #[URL(as: 'return_at')]
    public string $filter_return_at;
    #[URL(as: 'licence_plate')]
    public string $filter_licence_plate;
    #[URL(as: 'reservation_type')]
    public string $filter_reservation_type_id;

    protected $listeners = ['reset_history_filter', 'refresh_manage_history_mount'];

    public function mount() {
        $this->reservation_types = ReservationType::all();
    }

    public function refresh_manage_history_mount() {
        $this->mount();
    }

    public function reset_history_filter() {
        $this->reset('filter_name', 'filter_pickup_at', 'filter_return_at', 'filter_licence_plate');
        $this->resetPage();
    }

    public function filter_trips() {
        return Trip::with(['user', 'vehicle', 'reservationType'])->orderBy('return_at', 'desc')->when(
            isset($this->filter_name) && !empty($this->filter_name),
            function ($query) {
                return $query->whereHas('user', function ($inside_query) {
                    return $inside_query->where('name', 'LIKE', "%$this->filter_name%");
                });
            }
        )->when(
            isset($this->filter_licence_plate) && !empty($this->filter_licence_plate),
            function ($query) {
                return $query->whereHas('vehicle', function ($inside_query) {
                    return $inside_query->where('licence_plate', 'LIKE', "%$this->filter_licence_plate%");
                });
            }
        )->when(
            isset($this->filter_pickup_at) && !empty($this->filter_pickup_at),
            function ($query) {
                return $query->where('pickup_at', 'LIKE', "%$this->filter_pickup_at%");
            }
        )->when(
            isset($this->filter_return_at) && !empty($this->filter_return_at),
            function ($query) {
                return $query->where([['is_closed', '=', true], ['return_at', 'LIKE', "%$this->filter_return_at%"]]);
            }
        )->when(
            isset($this->filter_reservation_type_id) && !empty($this->filter_reservation_type_id),
            function ($query) {
                return $query->whereHas('reservationType', function ($inside_query) {
                    return $inside_query->where('id', '=', $this->filter_reservation_type_id);
                });
            }
        )->paginate(10);
    }

    public function render() {
        return view('livewire.admin.manage-history', [
            'trips' => $this->filter_trips()
        ]);
    }
}

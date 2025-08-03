<?php

namespace App\Livewire;

use App\Models\Trip;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

class History extends Component {
    use WithPagination;

    #[URL(as: 'pickup_at')]
    public string $filter_pickup_at;
    #[URL(as: 'return_at')]
    public string $filter_return_at;
    #[URL(as: 'licence_plate')]
    public string $filter_licence_plate;

    protected $listeners = ['reset_history_filter'];

    public function reset_history_filter() {
        $this->reset('filter_name', 'filter_pickup_at', 'filter_return_at', 'filter_licence_plate');
        $this->resetPage();
    }

    public function filter_trips() {
        return Trip::with(['vehicle'])->where('user_id', auth()->user()->id)->orderBy('return_at', 'desc')->when(
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
            isset($this->filter_licence_plate) && !empty($this->filter_licence_plate),
            function ($query) {
                return $query->whereHas('vehicle', function ($inside_query) {
                    return $inside_query->where('licence_plate', 'LIKE', "%$this->filter_licence_plate%");
                });
            }
        )->paginate(10);
    }

    public function render() {
        return view('livewire.history', [
            'trips' => $this->filter_trips()
        ]);
    }
}

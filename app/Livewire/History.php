<?php

namespace App\Livewire;

use App\Models\ReservationType;
use Carbon\Carbon;
use App\Models\Trip;
use DateTimeZone;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

class History extends Component {
    use WithPagination;

    public Collection $reservation_types;

    #[URL(as: 'pickup_at')]
    public string $filter_pickup_at;
    #[URL(as: 'is_closed')]
    public bool $filter_is_closed;
    #[URL(as: 'return_at')]
    public string $filter_return_at;
    #[URL(as: 'licence_plate')]
    public string $filter_licence_plate;
    #[URL(as: 'reservation_type')]
    public string $filter_reservation_type_id;

    protected $listeners = ['reset_history_filter'];

    public function mount() {
        $this->reservation_types = ReservationType::all();
    }

    public function reset_history_filter() {
        $this->reset('filter_pickup_at', 'filter_return_at', 'filter_licence_plate');
        $this->resetPage();
    }

    public function filter_trips() {
        return Trip::with(['vehicle', 'reservationType'])->where('user_id', auth()->user()->id)->orderBy('return_at', 'desc')->when(
            isset($this->filter_pickup_at) && !empty($this->filter_pickup_at),
            function ($query) {
                $localDate = Carbon::parse($this->filter_pickup_at, new DateTimeZone('Europe/Budapest'));
                $startUtc = $localDate->copy()->startOfDay()->timezone('UTC');
                $endUtc = $localDate->copy()->endOfDay()->timezone('UTC');

                return $query->whereBetween('pickup_at', [$startUtc, $endUtc]);
            }
        )->when(
            isset($this->filter_is_closed) && $this->filter_is_closed == true,
            function ($query) {
                return $query->where('is_closed', '=', true);
            }
        )->when(
            isset($this->filter_return_at) && !empty($this->filter_return_at),
            function ($query) {
                $localDate = Carbon::parse($this->filter_return_at, new DateTimeZone('Europe/Budapest'));
                $startUtc = $localDate->copy()->startOfDay()->timezone('UTC');
                $endUtc = $localDate->copy()->endOfDay()->timezone('UTC');

                return $query->where('is_closed', '=', true)->whereBetween('pickup_at', [$startUtc, $endUtc]);
            }
        )->when(
            isset($this->filter_licence_plate) && !empty($this->filter_licence_plate),
            function ($query) {
                return $query->whereHas('vehicle', function ($inside_query) {
                    return $inside_query->where('licence_plate', 'LIKE', "%$this->filter_licence_plate%");
                });
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
        return view('livewire.history', [
            'trips' => $this->filter_trips()
        ]);
    }
}

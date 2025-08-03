<?php

namespace App\Livewire\Forms;

use DateTimeZone;
use Carbon\Carbon;
use Livewire\Form;
use App\Models\Trip;
use Exception;

class TripForm extends Form {
    public string|null $name;
    public string|null $pickup_at;
    public string|null $return_at;
    public string|null $licence_plate;

    public Trip|null $trip;

    public function rules() {
        return [
            'pickup_at' => 'required|date',
            'return_at' => 'nullable|date',
        ];
    }

    public function messages() {
        return [
            'pickup_at.require' => 'Felvétel időpont megadása kötelező',
            'pickup_at.date' => 'Felvételnek dátumnak kell lennie',
            'return_at.date' => 'Leadásnak dátumnak kell lennie',
        ];
    }

    public function load_trip($trip_id) {
        $this->trip = Trip::where('id', $trip_id)->with(['user', 'vehicle'])->orderby('return_at', 'desc')->first();
        $this->name = $this->trip->user->name;

        $utc_pickup_at = Carbon::parse($this->trip->pickup_at, new DateTimeZone('UTC'));
        $local_pickup_at = $utc_pickup_at->copy()->timezone('Europe/Budapest');

        $this->pickup_at = $local_pickup_at;

        if ($this->trip->is_closed) {
            $utc_return_at = Carbon::parse($this->trip->return_at, new DateTimeZone('UTC'));
            $local_return_at = $utc_return_at->copy()->timezone('Europe/Budapest');

            $this->return_at =   $local_return_at;
        }

        $this->licence_plate = $this->trip->vehicle->licence_plate;
    }

    public function update() {
        $this->validate();

        $local_pickup_at = Carbon::parse($this->pickup_at, new DateTimeZone('Europe/Budapest'));
        $utc_pickup_at  = $local_pickup_at->copy()->timezone('UTC');
        $this->trip->pickup_at = $utc_pickup_at;

        if (!empty($this->return_at)) {
            if (Carbon::parse($this->pickup_at) > Carbon::parse($this->return_at))
                throw new Exception('A jármű leadási időpontja nem lehet korábbi, mint a felvétel időpontja');

            $local_return_at = Carbon::parse($this->return_at, new DateTimeZone('Europe/Budapest'));
            $utc_return_at  = $local_return_at->copy()->timezone('UTC');
            $this->trip->return_at = $utc_return_at;

            $this->trip->is_closed = true;
        } else {
            $this->trip->is_closed = false;
        }

        $this->trip->save();
    }

    public function delete() {
        $delete_result = $this->trip->delete();

        if (!isset($delete_result))
            throw new Exception('Törölni kívánt gépjármű nem található.');

        $this->reset();
    }
}

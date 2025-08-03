<?php

namespace App\Livewire\Forms;

use Exception;
use Livewire\Form;
use App\Models\Vehicle;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;

class VehicleForm extends Form {
    public Vehicle|null $vehicle;

    public string|null $licence_plate;
    public string|null $type;
    public int $status_id = 1;
    public string|null $vin;

    public function update_rules() {
        return [
            'licence_plate' => [
                'required',
                Rule::unique('vehicles', 'licence_plate')->ignore($this->vehicle->id),
            ],
            'type' => 'required',
            'status_id' => 'required',
            'vin' => [
                'required',
                Rule::unique('vehicles', 'vin')->ignore($this->vehicle->id),
            ]
        ];
    }

    public function create_rules() {
        return [
            'licence_plate' => [
                'required',
                Rule::unique('vehicles', 'licence_plate'),
            ],
            'type' => 'required',
            'status_id' => 'required',
            'vin' => [
                'required',
                Rule::unique('vehicles', 'vin'),
            ]
        ];
    }

    public function messages() {
        return [
            'licence_plate.required' => 'Rendszám megadása kötelező',
            'licence_plate.unique' => 'Rendszámnak egyedinek kell lennie',
            'type.required' => 'Típus megadása kötelező',
            'status_id.required' => 'Státusz megadása kötelező',
            'vin.required' => "Alvázszám megadása kötelező",
            'vin.unique' => "Alvázszámnak egyedinek kell lennie"
        ];
    }

    public function load_vehicle($vehicle_id) {
        $this->vehicle = Vehicle::where('id', '=', $vehicle_id)->with(['status'])->first();

        $this->licence_plate = $this->vehicle->licence_plate;
        $this->type = $this->vehicle->type;
        $this->status_id = $this->vehicle->status->id;
        $this->vin = $this->vehicle->vin;

        $this->reset("vehicle_ids");
    }

    public function update() {
        $this->validate($this->update_rules(), $this->messages());

        $this->vehicle->licence_plate = $this->licence_plate;
        $this->vehicle->type = $this->type;
        $this->vehicle->status->id = $this->status_id;
        $this->vehicle->vin = $this->vin;

        $this->vehicle->save();
    }

    public function delete() {
        $delete_result = $this->vehicle->delete();

        if (!isset($delete_result))
            throw new Exception('Törölni kívánt gépjármű nem található.');

        $this->reset();
    }

    public function create() {
        $this->validate($this->create_rules(), $this->messages());

        $vehicle = Vehicle::create([
            'licence_plate' => $this->licence_plate,
            'type' => $this->type,
            'vin' => $this->vin,
            'status_id' => $this->status_id
        ]);

        $vehicle->save();
    }
}

<?php

namespace App\Livewire\Admin\Components;

use Exception;
use Flux\Flux;
use Livewire\Component;
use App\Livewire\Forms\VehicleForm;
use Illuminate\Validation\ValidationException;

class VehiclesModal extends Component {

    public VehicleForm $form;
    public $statuses;

    public string|null $error_message;

    public function boot() {
        $this->error_message = null;
    }

    public $listeners = ['update_vehicle_load', 'update_vehicle', 'create_vehicle_load'];

    public function update_vehicle_load($user_id) {
        $this->resetValidation();
        $this->form->load_vehicle($user_id);
    }

    public function create_vehicle_load() {
        $this->resetValidation();
        $this->form->reset();
    }

    public function update_vehicle() {
        try {
            $this->form->update();

            $this->dispatch('vehicle_save_success');
            $this->dispatch('remount_vehicles');
        } catch (ValidationException $err) {
            throw $err;
        } catch (Exception $err) {
            $this->error_message = $err->getMessage();

            $this->dispatch('remount_vehicles');
        }
    }

    public function delete_vehicle() {
        try {
            $this->form->delete();

            $this->dispatch('vehicle_save_success');
            $this->dispatch('vehicle_delete_success');
            $this->dispatch('remount_vehicles');

            Flux::modals()->close();
        } catch (ValidationException $err) {
            throw $err;
        } catch (Exception $err) {
            $this->error_message = $err->getMessage();

            $this->dispatch('remount_vehicles');
        }
    }

    public function create_vehicle() {
        try {
            $this->form->create();
            $this->form->reset();

            $this->dispatch('vehicle_save_success');
            $this->dispatch('remount_vehicles');
        } catch (ValidationException $err) {
            throw $err;
        } catch (Exception $err) {
            $this->error_message = $err->getMessage();

            $this->dispatch('remount_vehicles');
        }
    }

    public function render() {
        return view('livewire.admin.components.vehicles-modal');
    }
}

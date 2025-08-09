<?php

namespace App\Livewire\Admin\Components;

use Exception;
use Flux\Flux;
use Livewire\Component;
use App\Livewire\Forms\TripForm;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class ManageHistoryModal extends Component {

    public TripForm $form;

    public Collection $reservation_types;

    public string|null $success_message;
    public string|null $error_message;

    protected $listeners = ['update_history'];

    public function boot() {
        $this->resetValidation();
        $this->success_message = null;
        $this->error_message = null;
    }

    public function update_history($trip_id) {
        $this->form->load_trip($trip_id);
    }

    public function update_history_save() {
        try {
            $this->form->update();

            $this->success_message = "Előzmény mentése sikeres.";
            $this->dispatch('refresh_manage_history_mount');
            $this->dispatch('history_save_success');
        } catch (ValidationException $err) {
            throw $err;
        } catch (Exception $err) {
            $this->error_message = $err->getMessage();
        }
    }

    public function delete_history() {
        try {
            $this->form->delete();

            $this->success_message = "Előzmény törlése sikeres.";
            $this->dispatch('refresh_manage_history_mount');
            $this->dispatch('history_save_success');
            $this->dispatch('close_history_modal');

            Flux::modals()->close();
        } catch (ValidationException $err) {
            throw $err;
        } catch (Exception $err) {
            $this->error_message = $err->getMessage();
        }
    }

    public function render() {
        return view('livewire.admin.components.manage-history-modal');
    }
}

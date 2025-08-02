<?php

namespace App\Livewire\Admin\Components;

use Exception;
use Flux\Flux;
use App\Models\User;
use App\Models\Vehicle;
use Livewire\Component;
use App\Livewire\Forms\UserForm;
use Illuminate\Validation\ValidationException;

class UserModal extends Component {

    public UserForm $form;
    public $statuses;
    public string $filter_licence_plate = "";

    public string|null $error_message;

    public function boot() {
        $this->error_message = null;
    }

    public $listeners = ['update_user_load', 'update_user', 'create_user_load'];

    public function update_user_load($user_id) {
        $this->resetValidation();
        $this->form->load_user($user_id);
    }

    public function create_user_load() {
        $this->resetValidation();
        $this->form->reset();
    }

    public function update_user() {
        try {
            $this->form->update();

            $this->dispatch('user_save_success');
            $this->dispatch('remount_users');
        } catch (ValidationException $err) {
            throw $err;
        } catch (Exception $err) {
            $this->error_message = $err->getMessage();

            $this->dispatch('remount_users');
        }
    }

    public function delete_user() {
        try {
            $this->form->delete();

            $this->dispatch('user_save_success');
            $this->dispatch('user_delete_success');
            $this->dispatch('remount_users');

            Flux::modals()->close();
        } catch (ValidationException $err) {
            throw $err;
        } catch (Exception $err) {
            $this->error_message = $err->getMessage();

            $this->dispatch('remount_users');
        }
    }

    public function create_user() {
        try {
            $this->form->create();
            $this->form->reset();

            $this->dispatch('user_save_success');
            $this->dispatch('remount_users');
        } catch (ValidationException $err) {
            throw $err;
        } catch (Exception $err) {
            $this->error_message = $err->getMessage();

            $this->dispatch('remount_users');
        }
    }

    public function filter_vehicles() {
        return Vehicle::where('licence_plate', 'LIKE', "%$this->filter_licence_plate%")->get();
    }

    public function render() {
        return view(
            'livewire.admin.components.user-modal',
            ['vehicles' => $this->filter_vehicles()]
        );
    }
}

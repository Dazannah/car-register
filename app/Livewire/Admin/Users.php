<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\Status;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class Users extends Component {
    use WithPagination;

    #[URL(as: 'name')]
    public string $filter_name;
    #[URL(as: 'username')]
    public string $filter_username;
    #[URL(as: 'status')]
    public string $filter_status;
    #[URL(as: 'licence_plate')]
    public string $filter_licence_plate;

    public function mount() {
    }

    protected $listeners = ['remount_users', 'reset_users_filter'];

    public function remount_users() {
        $this->mount();
    }

    public function reset_users_filter() {
        $this->reset('filter_name', 'filter_username', 'filter_status', 'filter_licence_plate');
        $this->resetPage();
        $this->dispatch('remount_users');
    }

    public function filter_users() {
        return User::with(['status', 'vehicles'])->when(
            isset($this->filter_name) && !empty($this->filter_name),
            function ($query) {
                return $query->where('name', 'LIKE', "%$this->filter_name%");
            }
        )->when(
            isset($this->filter_username) && !empty($this->filter_username),
            function ($query) {
                return $query->where('username', 'LIKE', "%$this->filter_username%");
            }
        )->when(
            isset($this->filter_status) && !empty($this->filter_status),
            function ($query) {
                return $query->whereHas('status', function ($inside_query) {
                    return $inside_query->where('technical_name', '=', "$this->filter_status");
                });
            }
        )->when(
            isset($this->filter_licence_plate) && !empty($this->filter_licence_plate),
            function ($query) {
                return $query->whereHas('vehicles', function ($inside_query) {
                    return $inside_query->where('licence_plate', 'LIKE', "%$this->filter_licence_plate%");
                });
            }
        )->paginate(10);
    }

    public function statuses() {
        return Status::all();
    }

    public function render() {
        return view('livewire.admin.users', [
            'users' => $this->filter_users(),
            'statuses' => $this->statuses()
        ]);
    }
}

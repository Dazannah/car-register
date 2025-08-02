<?php

namespace App\Livewire\Admin;

use App\Models\Status;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Users extends Component {
    use WithPagination;

    public function mount() {
    }

    protected $listeners = ['remount_users'];

    public function remount_users() {
        $this->mount();
    }

    public function filter_users() {
        return User::select(['id', 'name', 'username', 'status_id'])->with(['status', 'vehicles'])->paginate(10);
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

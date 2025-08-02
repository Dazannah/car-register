<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Users extends Component {
    use WithPagination;

    public function filter_users() {
        return User::select(['name', 'username', 'status_id'])->with('status')->paginate(10);
    }

    public function render() {
        return view('livewire.admin.users', [
            'users' => $this->filter_users()
        ]);
    }
}

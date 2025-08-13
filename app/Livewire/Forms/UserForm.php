<?php

namespace App\Livewire\Forms;

use Exception;
use Livewire\Form;
use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Hash;

class UserForm extends Form {

    public User|null $user;

    public string|null $name;
    public string|null $username;
    public int $status_id = 1;
    public string|null $password;
    public string|null $password_confirmed;
    public array $vehicle_ids = [];
    public bool $is_admin = false;

    public function update_rules() {
        return [
            'name' => 'required',
            'username' => [
                'required',
                Rule::unique('users', 'username')->ignore($this->user->id),
            ],
            'status_id' => 'required',
            'password' => 'nullable|same:password_confirmed',
        ];
    }

    public function create_rules() {
        return [
            'name' => 'required',
            'username' => [
                'required',
                Rule::unique('users', 'username'),
            ],
            'status_id' => 'required|exists:App\Models\Status,id',
            'password' => 'required|same:password_confirmed',
        ];
    }

    public function messages() {
        return [
            'name.required' => 'Név megadása kötelező',
            'username.required' => 'Felhasználónév megadása kötelező',
            'username.unique' => 'Felhasználónév már foglalt',
            'status_id.required' => 'Státusz megadása kötelező',
            'password.required' => 'Jelszó megadása kötelező',
            'password.confirmed' => 'A jelszavak nem egyeznek'
        ];
    }

    public function load_user($user_id) {
        $this->user = User::where('id', '=', $user_id)->with(['status', 'vehicles'])->first();

        $this->name = $this->user->name;
        $this->username = $this->user->username;
        $this->status_id = $this->user->status->id;
        $this->is_admin = $this->user->is_admin;

        $this->reset("vehicle_ids");

        foreach ($this->user->vehicles as $vehicle) {
            array_push($this->vehicle_ids, $vehicle->id);
        }
    }

    public function update() {
        $this->validate($this->update_rules(), $this->messages());

        $this->user->name = $this->name;
        $this->user->username = $this->username;
        $this->user->status_id = $this->status_id;
        $this->user->is_admin = $this->is_admin;
        if (!empty($this->password))
            $this->user->password = Hash::make($this->password);

        $this->user->save();

        $this->user->vehicles()->sync($this->vehicle_ids);
    }

    public function delete() {
        $delete_result = $this->user->delete();

        if (!isset($delete_result))
            throw new Exception('Törölni kívánt fiók nem található.');

        $this->reset();
    }

    public function create() {
        $this->validate($this->create_rules(), $this->messages());

        $user = User::create([
            'name' => $this->name,
            'username' => $this->username,
            'status_id' => $this->status_id,
            'password' => Hash::make($this->password),
            'is_admin' => $this->is_admin
        ]);

        $user->save();

        $user->vehicles()->sync($this->vehicle_ids);
    }
}

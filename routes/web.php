<?php

use Livewire\Volt\Volt;
use App\Livewire\History;
use App\Livewire\Dashboard;
use App\Livewire\Admin\Users;
use App\Http\Middleware\IsAdmin;
use App\Livewire\Admin\Vehicles;
use App\Livewire\Admin\ManageHistory;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
})->name('home');



Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');

    Route::get('/dashboard', Dashboard::class)
        ->name('dashboard');

    Route::get('/history', History::class)
        ->name('history');
});

Route::middleware(['auth', IsAdmin::class])->prefix('admin')->group(function () {
    Route::get('/users', Users::class)
        ->name('users');

    Route::get('/vehicles', Vehicles::class)
        ->name('vehicles');


    Route::get('/manage-history', ManageHistory::class)
        ->name('manage-history');
});




require __DIR__ . '/auth.php';

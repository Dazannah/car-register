<?php

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    #[Validate('required|string')]
    public string $username = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->ensureIsNotRateLimited();

        if (!Auth::attempt(['username' => $this->username, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'username' => __('auth.failed'),
            ]);
        }

        if (auth()->user()->status_id == 2) {
            RateLimiter::hit($this->throttleKey());

            auth()->logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();

            throw ValidationException::withMessages([
                'username' => __('Fiók inaktiválva van.'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'username' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->username) . '|' . request()->ip());
    }
}; ?>

<div class="flex flex-col gap-6">
    <x-auth-header :title="__('Jelentkezz be a fiókodba')" :description="__('Add meg az felhasználónevedet és a jelszavad a bejelentkezéshez')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="login" class="flex flex-col gap-6">
        <!-- Username Address -->
        <flux:input wire:model="username" :label="__('Felhasználónév')" type="text" required autofocus
            autocomplete="username" placeholder="Felhasználónév" />

        <!-- Password -->
        <div class="relative">
            <flux:input wire:model="password" :label="__('Jelszó')" type="password" required
                autocomplete="current-password" :placeholder="__('Jelszó')" viewable />
        </div>

        <!-- Remember Me -->
        <flux:checkbox wire:model="remember" :label="__('Emlékezz rám')" />

        <div class="flex items-center justify-end">
            <flux:button variant="primary" type="submit" class="w-full hover:cursor-pointer">{{ __('Bejelentkezés') }}
            </flux:button>
        </div>
    </form>
</div>

<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Megjelenés')" :subheading="__('Frissítsd a fiókod megjelenési beállításait')">
        <flux:radio.group x-data variant="segmented" x-model="$flux.appearance">
            <flux:radio class="hover:cursor-pointer" value="light" icon="sun">{{ __('Világos') }}</flux:radio>
            <flux:radio class="hover:cursor-pointer" value="dark" icon="moon">{{ __('Sötét') }}</flux:radio>
            <flux:radio class="hover:cursor-pointer" value="system" icon="computer-desktop">{{ __('Rendszer') }}
            </flux:radio>
        </flux:radio.group>
    </x-settings.layout>
</section>

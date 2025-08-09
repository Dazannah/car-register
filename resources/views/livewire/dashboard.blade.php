<div x-data="{ show_manage: false }"
    class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl text-slate-800 dark:text-gray-200">
    <div class="flex justify-center items-center w-full">
        <x-action-message-success class="me-3" on="show_success">
            {{ $success_message }}
        </x-action-message-success>

        @if (!empty($error_message))
            <flux:text color="red" class="mt-2">{{ $error_message }}</flux:text>
        @endif
    </div>

    <div class="grid auto-rows-min gap-4 md:grid-cols-3">
        @foreach ($user_vehicles as $user_vehicle)
            <div
                class="relative aspect-auto overflow-hidden overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900">
                <div class="p-3 text-center">
                    <div class="flex justify-center mb-4">
                        <h5 class="text-2xl font-semibold">
                            {{ $user_vehicle->licence_plate }}
                        </h5>
                    </div>
                    <div class="flex justify-center mb-2">
                        <p class="block leading-normal font-light mb-4 max-w-lg">
                            {{ $user_vehicle->type }}
                        </p>
                    </div>
                    <div class="flex justify-center mb-4">
                        @if (count($user_vehicle->trips) > 0)
                            <div>
                                <h5 class="text-2xl text-red-600 font-semibold">
                                    Foglalt
                                </h5>
                                <p class="block leading-normal font-light mb-4 max-w-lg">
                                    Felvette: {{ $user_vehicle->trips[0]->user->name }}
                                </p>
                                <flux:input type="datetime-local"
                                    value="{{ $user_vehicle->trips[0]->pickup_at->setTimezone('Europe/Budapest')->format('Y-m-d\TH:i') }}"
                                    disabled />
                            </div>
                        @else
                            <div>
                                <h5 class="text-2xl text-green-600 font-semibold">
                                    Elérhető
                                </h5>
                                <p class="block leading-normal font-light mb-4 max-w-lg">
                                    Felveszi: {{ auth()->user()->name }}
                                </p>
                                <flux:input wire:model="pickup_time" type="datetime-local" max="2999-12-31" />
                            </div>
                        @endif
                    </div>
                    <div class="text-center">
                        <div class="flex justify-center items-center w-full">
                            <flux:icon.loading wire:loading />
                        </div>
                        <div wire:loading.remove>
                            @if (count($user_vehicle->trips) > 0)
                                @if ($user_vehicle->trips[0]->user_id == auth()->user()->id)
                                    <flux:button
                                        @click.prevent="$wire['return_vehicle']({{ $user_vehicle->trips[0]->id }})"
                                        variant="primary" color="red" class="w-full hover:cursor-pointer">
                                        <flux:icon.warehouse />
                                        {{ __('Leadás') }}
                                    </flux:button>
                                @endif
                            @else
                                <flux:button @click.prevent="$wire['pickup_vehicle']({{ $user_vehicle->id }})"
                                    variant="primary" color="green" class="w-full hover:cursor-pointer">
                                    <flux:icon.car-front />
                                    {{ __('Felvétel') }}
                                </flux:button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

    </div>
</div>

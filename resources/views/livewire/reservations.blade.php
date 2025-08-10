<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl text-slate-800 dark:text-gray-200">
    <div class="flex justify-center items-center w-full">
        <x-action-message-success class="me-3" on="show_success">
            {{ $success_message }}
        </x-action-message-success>

        @if (!empty($error_message))
            <flux:text color="red" class="mt-2">{{ $error_message }}</flux:text>
        @endif
    </div>

    <div class="grid auto-rows-min gap-4 md:grid-cols-3">
        <div
            class="relative aspect-auto overflow-hidden overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900">
            <div class="p-3 text-center">
                <div class="flex justify-center mb-4">
                    <h5 class="text-2xl font-semibold">
                        Új előfoglalás
                    </h5>
                </div>

                <div class="flex justify-center mb-2">
                    <flux:select wire:model.live="booking_vehicle_id">
                        <flux:select.option value="">Válassz gépjárművet</flux:select.option>
                        @foreach ($user_vehicles as $user_vehicle)
                            <flux:select.option value="{{ $user_vehicle->id }}">
                                {{ "$user_vehicle->licence_plate - $user_vehicle->type" }}
                            </flux:select.option>
                        @endforeach
                    </flux:select>
                </div>

                <div class="flex flex-col justify-center gap-2 mb-2">
                    @if (isset($booking_vehicle_id))
                        <flux:input wire:model.live="booking_start" type="datetime-local" label="Felvétel időpontja" />
                    @endif

                    @if ($interfere_with_start)
                        <div
                            class="relative aspect-auto overflow-hidden overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900">
                            <div class="p-3 text-center">
                                A gépjármű ezzel a kezdéssel nem elérhető.<br />
                                Foglalás adatai:<br />
                                Név: {{ $interfere_with_start->user->name }}

                                <flux:input
                                    value="{{ $interfere_with_start->pickup_at->setTimezone('Europe/Budapest')->format('Y-m-d\TH:i') }}"
                                    type="datetime-local" label="Felvétel" disabled />
                                @if ($interfere_with_start->pickup_at != $interfere_with_start->return_at)
                                    <flux:input
                                        value="{{ $interfere_with_start->return_at->setTimezone('Europe/Budapest')->format('Y-m-d\TH:i') }}"
                                        type="datetime-local" label="Leadás" disabled />
                                @endif
                            </div>
                        </div>
                    @endif

                    @if (isset($booking_start) && $is_booking_start_available)
                        <flux:input wire:model.live="booking_end" type="datetime-local" label="Leadás időpontja" />
                    @endif

                    @if ($interfere_with_end)
                        <div
                            class="relative aspect-auto overflow-hidden overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900">
                            <div class="p-3 text-center">
                                A gépjármű ezzel a leadással nem elérhető.<br />
                                Foglalás adatai:<br />
                                Név: {{ $interfere_with_end->user->name }}

                                <flux:input
                                    value="{{ $interfere_with_end->pickup_at->setTimezone('Europe/Budapest')->format('Y-m-d\TH:i') }}"
                                    type="datetime-local" label="Felvétel" disabled />
                                @if ($interfere_with_end->pickup_at != $interfere_with_end->return_at)
                                    <flux:input
                                        value="{{ $interfere_with_end->return_at->setTimezone('Europe/Budapest')->format('Y-m-d\TH:i') }}"
                                        type="datetime-local" label="Leadás" disabled />
                                @endif
                            </div>
                        </div>
                    @endif
                    @if ($interfere_with_the_whole_time)
                        <div
                            class="relative aspect-auto overflow-hidden overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900">
                            <div class="p-3 text-center">
                                Idősáv ütközés<br />
                                Foglalás adatai:<br />
                                Név: {{ $interfere_with_the_whole_time->user->name }}

                                <flux:input
                                    value="{{ $interfere_with_the_whole_time->pickup_at->setTimezone('Europe/Budapest')->format('Y-m-d\TH:i') }}"
                                    type="datetime-local" label="Felvétel" disabled />
                                @if ($interfere_with_the_whole_time->pickup_at != $interfere_with_the_whole_time->return_at)
                                    <flux:input
                                        value="{{ $interfere_with_the_whole_time->return_at->setTimezone('Europe/Budapest')->format('Y-m-d\TH:i') }}"
                                        type="datetime-local" label="Leadás" disabled />
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <div class="text-center">
                    <div class="flex justify-center items-center w-full">
                        <flux:icon.loading wire:loading />
                    </div>
                    <div wire:show="is_booking_time_available" x-cloak>
                        <flux:button wire:loading.remove @click.prevent="$wire['prebook_vehicle']()" variant="primary"
                            color="green" class="w-full hover:cursor-pointer">
                            <flux:icon.notebook-pen />
                            {{ __('Előfoglalás') }}
                        </flux:button>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
        <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
    </div>
</div>

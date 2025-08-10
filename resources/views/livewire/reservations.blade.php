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

    <div
        class="relative flex flex-col w-full text-slate-700 bg-white dark:bg-neutral-900 shadow-md rounded-xl bg-clip-border">
        <div
            class="relative mx-4 mt-4 overflow-hidden text-slate-700 bg-white dark:bg-neutral-900 rounded-none bg-clip-border">
            <div class="flex items-center justify-between ">
                <div>
                    <h3 class="text-lg font-semibold text-slate-800 dark:text-gray-200">Előfoglalások</h3>
                </div>
                <div class="flex flex-col gap-2 shrink-0 sm:flex-row">
                    <flux:button @click.prevent="show_new = true" variant="primary" class="w-full hover:cursor-pointer">
                        <flux:icon.calendar-plus />
                        {{ __('Új előfoglalás') }}
                    </flux:button>
                </div>
            </div>

        </div>
        <div class="p-0 overflow-scroll">
            <table class="w-full mt-4 text-left table-auto min-w-max">
                <thead>
                    <tr>
                        <th
                            class="p-2 transition-colors border-y border-slate-200 bg-slate-50 hover:bg-slate-100 dark:bg-neutral-900 dark:hover:bg-neutral-800">
                            <flux:input size="sm" placeholder="Rendszám" wire:model.live="filter_licence_plate" />
                        </th>
                        <th
                            class="p-2 transition-colors border-y border-slate-200 bg-slate-50 hover:bg-slate-100 dark:bg-neutral-900 dark:hover:bg-neutral-800">
                            <flux:input size="sm" placeholder="Név" wire:model.live="filter_name" />
                        </th>
                        <th
                            class="p-2 transition-colors border-y border-slate-200 bg-slate-50 hover:bg-slate-100 dark:bg-neutral-900 dark:hover:bg-neutral-800">
                            <flux:field variant="inline">
                                <flux:checkbox wire:model.live="filter_is_closed" />
                                <flux:label>lezárt</flux:label>
                                <flux:error name="filter_is_closed" />
                            </flux:field>
                        </th>
                        <th
                            class="p-2 transition-colors border-y border-slate-200 bg-slate-50 hover:bg-slate-100 dark:bg-neutral-900 dark:hover:bg-neutral-800">
                            <flux:field>
                                <flux:label>Felvéve</flux:label>
                                <input class="text-slate-700 dark:text-gray-200" type="date"
                                    wire:model.live="filter_pickup_at">
                            </flux:field>
                        </th>
                        <th
                            class="p-2 transition-colors border-y border-slate-200 bg-slate-50 hover:bg-slate-100 dark:bg-neutral-900 dark:hover:bg-neutral-800">
                            <flux:field>
                                <flux:label>Leadva</flux:label>
                                <input class="text-slate-700 dark:text-gray-200" type="date"
                                    wire:model.live="filter_return_at">
                            </flux:field>
                        </th>
                        <th
                            class="p-2 transition-colors border-y border-slate-200 bg-slate-50 hover:bg-slate-100 dark:bg-neutral-900 dark:hover:bg-neutral-800">
                            <flux:button @click.prevent="$wire['reset_reservation_filter']()" variant="primary"
                                class="w-full hover:cursor-pointer">
                                <flux:icon.list-restart />
                            </flux:button>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reservations as $reservation)
                        <tr>
                            <td class="p-2 border-b border-slate-200">
                                <div class="flex items-center gap-3">
                                    <div class="flex flex-col p-1 bg-white dark:bg-neutral-900">
                                        <p class="text-sm font-semibold text-slate-700 dark:text-gray-200 uppercase">
                                            {{ $reservation->vehicle->licence_plate }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="sticky left-0 z-10 p-2 border-b border-slate-200">
                                <div class="flex items-center gap-3">
                                    <div class="flex flex-col p-1 bg-white dark:bg-neutral-900">
                                        <p class="text-sm font-semibold text-slate-700 dark:text-gray-200">
                                            {{ $reservation->user->name }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="p-2 border-b border-slate-200">
                                <div class="flex items-center gap-3">
                                    <div class="flex flex-col p-1 bg-white dark:bg-neutral-900">
                                        <p class="text-sm font-semibold text-slate-700 dark:text-gray-200 uppercase">
                                            @if ($reservation->is_closed)
                                                <flux:icon.lock />
                                            @else
                                                <flux:icon.lock-open />
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="p-2 border-b border-slate-200">
                                <div class="flex items-center gap-3">
                                    <div class="flex flex-col">
                                        <input class="text-slate-700 dark:text-gray-200" type="datetime-local"
                                            name="pickup-time"
                                            value="{{ $reservation->pickup_at->setTimezone('Europe/Budapest')->format('Y-m-d\TH:i') }}"
                                            disabled>
                                    </div>
                                </div>
                            </td>
                            <td class="p-2 border-b border-slate-200">
                                <div class="flex flex-col">
                                    @if ($reservation->is_closed || $reservation->reservationType->technical_name == 'pre')
                                        <input class="text-slate-700 dark:text-gray-200" type="datetime-local"
                                            name="return-time"
                                            value="{{ $reservation->return_at->setTimezone('Europe/Budapest')->format('Y-m-d\TH:i') }}"
                                            disabled>
                                    @endif

                                </div>
                            </td>

                            <td class="p-2 border-b border-slate-200">
                                {{-- <button
                                        @click.prevent="show_update = true; update_history_id = {{ $reservation->id }}"
                                        class="relative cursor-pointer h-10 max-h-[40px] w-10 max-w-[40px] select-none rounded-lg text-center align-middle font-sans text-xs font-medium uppercase text-slate-900 dark:text-gray-200 transition-all hover:bg-slate-900/10 active:bg-slate-900/20 dark:hover:bg-slate-400/10 dark:active:bg-slate-400/20 disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                                        type="button">
                                        <span
                                            class="absolute transform -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2">
                                            <flux:icon.pencil />
                                        </span>
                                    </button> --}}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="flex items-center justify-between p-3">
            {{ $reservations->links() }}
        </div>
    </div>
</div>

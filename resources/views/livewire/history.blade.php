<div x-data="{ show_update: false }">
    <div
        class="relative flex flex-col w-full h-full text-slate-700 bg-white dark:bg-neutral-900 shadow-md rounded-xl bg-clip-border">
        <div
            class="relative mx-4 mt-4 overflow-hidden text-slate-700 bg-white dark:bg-neutral-900 rounded-none bg-clip-border">
            <div class="flex items-center justify-between ">
                <div>
                    <h3 class="text-lg font-semibold text-slate-800 dark:text-gray-200">Előzményeim</h3>
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
                            <flux:button @click.prevent="$wire['reset_history_filter']()" variant="primary"
                                class="w-full hover:cursor-pointer">
                                <flux:icon.list-restart />
                            </flux:button>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($trips as $trip)
                        <tr>
                            <td class="p-2 border-b border-slate-200">
                                <div class="flex items-center gap-3">
                                    <div class="flex flex-col p-1 bg-white dark:bg-neutral-900">
                                        <p class="text-sm font-semibold text-slate-700 dark:text-gray-200 uppercase">
                                            {{ $trip->vehicle->licence_plate }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="p-2 border-b border-slate-200">
                                <div class="flex items-center gap-3">
                                    <div class="flex flex-col">
                                        <input class="text-slate-700 dark:text-gray-200" type="datetime-local"
                                            value="{{ $trip->pickup_at->setTimezone('Europe/Budapest')->format('Y-m-d\TH:i') }}"
                                            disabled>
                                    </div>
                                </div>
                            </td>
                            <td class="p-2 border-b border-slate-200">
                                <div class="flex flex-col">
                                    @if ($trip->is_closed)
                                        <input class="text-slate-700 dark:text-gray-200" type="datetime-local"
                                            value="{{ $trip->return_at->setTimezone('Europe/Budapest')->format('Y-m-d\TH:i') }}"
                                            disabled>
                                    @endif

                                </div>
                            </td>

                            <td class="p-2 border-b border-slate-200">
                                {{-- <button @click.prevent="show_update = true; update_history_id = {{ 1 }}"
                                    class="relative cursor-pointer h-10 max-h-[40px] w-10 max-w-[40px] select-none rounded-lg text-center align-middle font-sans text-xs font-medium uppercase text-slate-900 dark:text-gray-200 transition-all hover:bg-slate-900/10 active:bg-slate-900/20 dark:hover:bg-slate-400/10 dark:active:bg-slate-400/20 disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                                    type="button">
                                    <span class="absolute transform -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2">
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
            {{ $trips->links() }}
        </div>
    </div>

    <livewire:admin.components.manage-history-modal />
</div>

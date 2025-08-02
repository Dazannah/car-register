<div x-data="{ {{-- show_new: false, --}} show_update: false }">
    <div
        class="relative flex flex-col w-full h-full text-slate-700 bg-white dark:bg-neutral-900 shadow-md rounded-xl bg-clip-border">
        <div
            class="relative mx-4 mt-4 overflow-hidden text-slate-700 bg-white dark:bg-neutral-900 rounded-none bg-clip-border">
            <div class="flex items-center justify-between ">
                <div>
                    <h3 class="text-lg font-semibold text-slate-800 dark:text-gray-200">Előzmények</h3>
                </div>
                {{-- <div class="flex flex-col gap-2 shrink-0 sm:flex-row">
                    <flux:button @click.prevent="show_new = true" variant="primary" class="w-full hover:cursor-pointer">
                        <flux:icon.file />
                        {{ __('Új hozzáadása') }}
                    </flux:button>
                </div> --}}
            </div>

        </div>
        <div class="p-0 overflow-scroll">
            <table class="w-full mt-4 text-left table-auto min-w-max">
                <thead>
                    <tr>
                        <th
                            class="p-2 transition-colors border-y border-slate-200 bg-slate-50 hover:bg-slate-100 dark:bg-neutral-900 dark:hover:bg-neutral-800">
                            <flux:input size="sm" placeholder="Név" />
                        </th>
                        <th
                            class="p-2 transition-colors border-y border-slate-200 bg-slate-50 hover:bg-slate-100 dark:bg-neutral-900 dark:hover:bg-neutral-800">
                            <flux:field>
                                <flux:label>Felvéve</flux:label>
                                <input type="datetime-local" name="pickup-time">
                            </flux:field>
                        </th>
                        <th
                            class="p-2 transition-colors border-y border-slate-200 bg-slate-50 hover:bg-slate-100 dark:bg-neutral-900 dark:hover:bg-neutral-800">
                            <flux:field>
                                <flux:label>Leadva</flux:label>
                                <input type="datetime-local" name="return-time">
                            </flux:field>
                        </th>
                        <th
                            class="p-2 transition-colors border-y border-slate-200 bg-slate-50 hover:bg-slate-100 dark:bg-neutral-900 dark:hover:bg-neutral-800">
                            <flux:input size="sm" placeholder="Rendszám" />
                        </th>

                        <th
                            class="p-2 transition-colors border-y border-slate-200 bg-slate-50 hover:bg-slate-100 dark:bg-neutral-900 dark:hover:bg-neutral-800">
                            <flux:button @click.prevent="reset_users_filter" variant="primary"
                                class="w-full hover:cursor-pointer">
                                <flux:icon.list-restart />
                            </flux:button>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="sticky left-0 z-100 p-2 border-b border-slate-200">
                            <div class="flex items-center gap-3">
                                <div class="flex flex-col p-1 bg-white dark:bg-neutral-900">
                                    <p class="text-sm font-semibold text-slate-700 dark:text-gray-200">
                                        Fábián Dávid
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="p-2 border-b border-slate-200">
                            <div class="flex items-center gap-3">
                                <div class="flex flex-col">
                                    <input type="datetime-local" name="return-time" value="2025-08-01 09:05" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="p-2 border-b border-slate-200">
                            <div class="flex flex-col">
                                <input type="datetime-local" name="return-time" value="2025-08-01 14:00" disabled>
                            </div>
                        </td>
                        <td class="p-2 border-b border-slate-200">
                            <div class="flex items-center gap-3">
                                <div class="flex flex-col p-1 bg-white dark:bg-neutral-900">
                                    <p class="text-sm font-semibold text-slate-700 dark:text-gray-200 uppercase">
                                        UPK502
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="p-2 border-b border-slate-200">
                            <button @click.prevent="show_update = true; update_history_id = {{ 1 }}"
                                class="relative cursor-pointer h-10 max-h-[40px] w-10 max-w-[40px] select-none rounded-lg text-center align-middle font-sans text-xs font-medium uppercase text-slate-900 dark:text-gray-200 transition-all hover:bg-slate-900/10 active:bg-slate-900/20 dark:hover:bg-slate-400/10 dark:active:bg-slate-400/20 disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                                type="button">
                                <span class="absolute transform -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2">
                                    <flux:icon.pencil />
                                </span>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="flex items-center justify-between p-3">
            <p class="block text-sm text-slate-500 dark:text-gray-400">
                Összesen: 10
            </p>
            <div class="flex gap-1">
                <flux:button @click.prevent="prewious_page" class="w-full hover:cursor-pointer">
                    <flux:icon.chevron-left />
                </flux:button>
                <flux:button @click.prevent="next_page" class="w-full hover:cursor-pointer">
                    <flux:icon.chevron-right />
                </flux:button>
            </div>
        </div>
    </div>

    <livewire:admin.components.manage-history-modal />
</div>

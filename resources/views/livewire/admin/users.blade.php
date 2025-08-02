<div x-data="{ show_new: false, show_update: false }">
    <div class="relative flex flex-col w-full h-full text-slate-700 bg-white shadow-md rounded-xl bg-clip-border">
        <div class="relative mx-4 mt-4 overflow-hidden text-slate-700 bg-white rounded-none bg-clip-border">
            <div class="flex items-center justify-between ">
                <div>
                    <h3 class="text-lg font-semibold text-slate-800">Felhasználók</h3>
                </div>
                <div class="flex flex-col gap-2 shrink-0 sm:flex-row">
                    <flux:button @click.prevent="show_new = true" variant="primary" class="w-full hover:cursor-pointer">
                        <flux:icon.user-plus />
                        {{ __('Új hozzáadása') }}
                    </flux:button>
                </div>
            </div>

        </div>
        <div class="p-0 overflow-scroll">
            <table class="w-full mt-4 text-left table-auto min-w-max">
                <thead>
                    <tr>
                        <th class="p-2 transition-colors border-y border-slate-200 bg-slate-50 hover:bg-slate-100">
                            <flux:input size="sm" placeholder="Név" />
                        </th>
                        <th class="p-2 transition-colors border-y border-slate-200 bg-slate-50 hover:bg-slate-100">
                            <flux:input size="sm" placeholder="Felhasználónév" />
                        </th>
                        <th class="p-2 transition-colors border-y border-slate-200 bg-slate-50 hover:bg-slate-100">
                            <flux:select wire:model="status_filter" size="sm" placeholder="Státusz">
                                <flux:select.option>Aktív</flux:select.option>
                                <flux:select.option>Inaktív services</flux:select.option>
                            </flux:select>
                        </th>
                        <th class="p-2 transition-colors border-y border-slate-200 bg-slate-50 hover:bg-slate-100">
                            <flux:input size="sm" placeholder="Rendszámok" />
                        </th>
                        <th class="p-2 transition-colors border-y border-slate-200 bg-slate-50 hover:bg-slate-100">
                            <p
                                class="flex items-center justify-between gap-2 font-sans text-sm  font-normal leading-none text-slate-500">
                            </p>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="p-2 border-b border-slate-200">
                            <div class="flex items-center gap-3">
                                <div class="flex flex-col">
                                    <p class="text-sm font-semibold text-slate-700">
                                        John Michael
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="p-2 border-b border-slate-200">
                            <div class="flex flex-col">
                                <p class="text-sm font-semibold text-slate-700">
                                    Manager
                                </p>
                            </div>
                        </td>
                        <td class="p-2 border-b border-slate-200">
                            <div class="w-max">
                                <div
                                    class="relative grid items-center px-2 py-1 font-sans text-xs font-bold text-green-900 uppercase rounded-md select-none whitespace-nowrap bg-green-500/20">
                                    <span class="">aktív</span>
                                </div>
                            </div>
                        </td>
                        <td class="p-2 border-b border-slate-200">
                            <p class="text-sm text-slate-500">
                                23/04/18
                            </p>
                        </td>
                        <td class="p-2 border-b border-slate-200">
                            <button @click.prevent="show_update = true; update_user_id = {{ 1 }}"
                                class="relative cursor-pointer h-10 max-h-[40px] w-10 max-w-[40px] select-none rounded-lg text-center align-middle font-sans text-xs font-medium uppercase text-slate-900 transition-all hover:bg-slate-900/10 active:bg-slate-900/20 disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                                type="button">
                                <span class="absolute transform -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2">
                                    <flux:icon.pencil />
                                </span>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td class="p-2 border-b border-slate-200">
                            <div class="flex items-center gap-3">
                                <div class="flex flex-col">
                                    <p class="text-sm font-semibold text-slate-700">
                                        John Michael
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="p-2 border-b border-slate-200">
                            <div class="flex flex-col">
                                <p class="text-sm font-semibold text-slate-700">
                                    Manager
                                </p>
                            </div>
                        </td>
                        <td class="p-2 border-b border-slate-200">
                            <div class="w-max">
                                <div
                                    class="relative grid items-center px-2 py-1 font-sans text-xs font-bold text-red-900 uppercase rounded-md select-none whitespace-nowrap bg-red-500/20">
                                    <span class="">inaktív</span>
                                </div>
                            </div>
                        </td>
                        <td class="p-2 border-b border-slate-200">
                            <p class="text-sm text-slate-500">
                                23/04/18
                            </p>
                        </td>
                        <td class="p-2 border-b border-slate-200">
                            <button
                                class="relative cursor-pointer h-10 max-h-[40px] w-10 max-w-[40px] select-none rounded-lg text-center align-middle font-sans text-xs font-medium uppercase text-slate-900 transition-all hover:bg-slate-900/10 active:bg-slate-900/20 disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
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
            {{-- <p class="block text-sm text-slate-500">
                Page 1 of 10
            </p>
            <div class="flex gap-1">
                <button
                    class="rounded border border-slate-300 py-2.5 px-3 text-center text-xs font-semibold text-slate-600 transition-all hover:opacity-75 focus:ring focus:ring-slate-300 active:opacity-[0.85] disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                    type="button">
                    Previous
                </button>
                <button
                    class="rounded border border-slate-300 py-2.5 px-3 text-center text-xs font-semibold text-slate-600 transition-all hover:opacity-75 focus:ring focus:ring-slate-300 active:opacity-[0.85] disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                    type="button">
                    Next
                </button>
            </div> --}}
        </div>
    </div>

    <livewire:admin.components.user-modal />
</div>

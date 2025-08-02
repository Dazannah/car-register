<div x-data="{ show_new: false, show_update: false }">
    <div
        class="relative flex flex-col w-full h-full text-slate-700 bg-white dark:bg-neutral-900 shadow-md rounded-xl bg-clip-border">
        <div
            class="relative mx-4 mt-4 overflow-hidden text-slate-700 bg-white dark:bg-neutral-900 rounded-none bg-clip-border">
            <div class="flex items-center justify-between ">
                <div>
                    <h3 class="text-lg font-semibold text-slate-800 dark:text-gray-200">Felhasználók</h3>
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
                        <th
                            class="p-2 transition-colors border-y border-slate-200 bg-slate-50 hover:bg-slate-100 dark:bg-neutral-900 dark:hover:bg-neutral-800">
                            <flux:input size="sm" placeholder="Név" />
                        </th>
                        <th
                            class="p-2 transition-colors border-y border-slate-200 bg-slate-50 hover:bg-slate-100 dark:bg-neutral-900 dark:hover:bg-neutral-800">
                            <flux:input size="sm" placeholder="Felhasználónév" />
                        </th>
                        <th
                            class="p-2 transition-colors border-y border-slate-200 bg-slate-50 hover:bg-slate-100 dark:bg-neutral-900 dark:hover:bg-neutral-800">
                            <flux:select wire:model="status_filter" size="sm">
                                <flux:select.option>Válassz státuszt</flux:select.option>
                                @foreach ($statuses as $status)
                                    <flux:select.option value="{{ $status->id }}">{{ $status->display_name }}
                                    </flux:select.option>
                                @endforeach
                            </flux:select>
                        </th>
                        <th
                            class="p-2 transition-colors border-y border-slate-200 bg-slate-50 hover:bg-slate-100 dark:bg-neutral-900 dark:hover:bg-neutral-800">
                            <flux:input size="sm" placeholder="Rendszámok" />
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
                    @foreach ($users as $user)
                        <tr>
                            <td class="sticky left-0 z-100 p-2 border-b border-slate-200">
                                <div class="flex items-center gap-3">
                                    <div class="flex flex-col p-1 bg-white dark:bg-neutral-900">
                                        <p class="text-sm font-semibold text-slate-700 dark:text-gray-200">
                                            {{ $user->name }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="p-2 border-b border-slate-200">
                                <div class="flex flex-col">
                                    <p class="text-sm font-semibold text-slate-700 dark:text-gray-200">
                                        {{ $user->username }}
                                    </p>
                                </div>
                            </td>
                            <td class="p-2 border-b border-slate-200">
                                <div class="w-max">
                                    @if ($user->status->technical_name == 'active')
                                        <div
                                            class="relative grid items-center px-2 py-1 font-sans text-xs font-bold text-green-900 uppercase rounded-md select-none whitespace-nowrap bg-green-500/20 dark:text-gray-200 dark:bg-green-500/80">
                                            <span class="">{{ $user->status->display_name }}</span>
                                        </div>
                                    @else
                                        <div
                                            class="relative grid items-center px-2 py-1 font-sans text-xs font-bold text-red-900 uppercase rounded-md select-none whitespace-nowrap bg-red-500/20 dark:text-gray-200 dark:bg-red-500/80">
                                            <span class="">{{ $user->status->display_name }}</span>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="p-2 border-b border-slate-200">
                                @foreach ($user->vehicles as $vehicle)
                                    <p class="text-sm text-slate-500 dark:text-gray-200 uppercase">
                                        {{ $vehicle->licence_plate }}
                                    </p>
                                @endforeach
                            </td>
                            <td class="p-2 border-b border-slate-200">
                                <button @click.prevent="show_update = true; update_user_id = {{ $user->id }}"
                                    class="relative cursor-pointer h-10 max-h-[40px] w-10 max-w-[40px] select-none rounded-lg text-center align-middle font-sans text-xs font-medium uppercase text-slate-900 dark:text-gray-200 transition-all hover:bg-slate-900/10 active:bg-slate-900/20 dark:hover:bg-slate-400/10 dark:active:bg-slate-400/20 disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                                    type="button">
                                    <span class="absolute transform -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2">
                                        <flux:icon.pencil />
                                    </span>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="flex items-center justify-between p-3">
            {{ $users->links(data: ['scrollTo' => false]) }}
            {{-- <p class="block text-sm text-slate-500 dark:text-gray-400">
                Összesen: 10
            </p>
            <div class="flex gap-1">

                <flux:button @click.prevent="prewious_page" class="w-full hover:cursor-pointer">
                    <flux:icon.chevron-left />
                </flux:button>
                <flux:button @click.prevent="next_page" class="w-full hover:cursor-pointer">
                    <flux:icon.chevron-right />
                </flux:button>
            </div> --}}
        </div>
    </div>

    <livewire:admin.components.user-modal :$statuses />
</div>

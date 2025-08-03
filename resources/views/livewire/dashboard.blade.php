<div x-data="{ show_manage: falsen }"
    class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl text-slate-800 dark:text-gray-200">
    <div class="grid auto-rows-min gap-4 md:grid-cols-3">
        <div
            class="relative aspect-auto overflow-hidden overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900">
            <div class="p-3 text-center">
                <div class="flex justify-center mb-4">
                    <h5 class="text-2xl font-semibold">
                        UPK502
                    </h5>
                </div>
                <div class="flex justify-center mb-2">
                    <p class="block leading-normal font-light mb-4 max-w-lg">
                        Honda CB500 1996
                    </p>
                </div>
                <div class="flex justify-center mb-4">
                    <div>
                        <h5 class="text-2xl text-red-600 font-semibold">
                            Foglalt
                        </h5>
                        <p class="block leading-normal font-light mb-4 max-w-lg">
                            Felvette: Teszt Elek
                        </p>
                        <input wire:model="pickup_time" type="datetime-local" disabled>
                    </div>
                    <div>
                        <h5 class="text-2xl text-green-600 font-semibold">
                            Elérhető
                        </h5>
                        <p class="block leading-normal font-light mb-4 max-w-lg">
                            Felveszi: Teszt Elek
                        </p>
                        <input wire:model="pickup_time" type="datetime-local">
                    </div>
                </div>
                <div class="text-center">
                    <flux:button @click.prevent="pickup_vehicle" variant="primary" color="green"
                        class="w-full hover:cursor-pointer">
                        <flux:icon.car-front />
                        {{ __('Felvétel') }}
                    </flux:button>
                    <flux:button @click.prevent="return_vehicle" variant="primary" color="red"
                        class="w-full hover:cursor-pointer">
                        <flux:icon.warehouse />
                        {{ __('Leadás') }}
                    </flux:button>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
        <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
    </div> --}}
</div>

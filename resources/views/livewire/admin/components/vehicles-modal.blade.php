<div x-data="{ show: false, method_name: '', modal_title: '' }" x-init="$watch('show_new', value => {
    show = value
    method_name = 'create_vehicles'
    modal_title = 'Gépjármű hozzáadása'

    if (value) $dispatch('create_user')
});
$watch('show_update', value => {
    show = value
    method_name = 'update_vehicles'
    modal_title = 'Gépjármű módosítása'

    if (value) $dispatch('update_vehicles', [update_vehicles_id])
});
$watch('show', value => {
    if (!value) {
        show_new = value
        show_update = value
    }
});" x-cloak x-show="show" x-transition data-dialog-backdrop="dialog"
    data-dialog-backdrop-close="true"
    class="absolute left-0 top-0 inset-0 z-[999] grid h-screen w-screen place-items-center bg-black/10 backdrop-blur-sm transition-opacity duration-300">
    <div data-dialog="dialog"
        class="relative mx-auto flex w-full max-w-[24rem] flex-col rounded-xl bg-white dark:bg-neutral-900 bg-clip-border text-slate-700 shadow-md">
        <div class="flex flex-col p-6">
            <div class="flex items-center justify-between">
                <flux:heading size="xl" x-text="modal_title"></flux:heading>
                <flux:button class="cursor-pointer" @click.prevent="show = false" icon="x-mark" variant="subtle" />
            </div>

            <div class="w-full max-w-sm min-w-[200px] mt-4">
                <flux:input wire:model="form.licence_plate" :label="__('Rendszám')" type="text" required autofocus
                    autocomplete="form.licence_plate" placeholder="Rendszám" />
            </div>
            <div class="w-full max-w-sm min-w-[200px] mt-4">
                <flux:input wire:model="form.vehicle_type" :label="__('Típus')" type="text" required
                    autocomplete="form.vehicle_type" placeholder="Típus" />
            </div>
            <div class="w-full max-w-sm min-w-[200px] mt-4">
                <flux:select wire:model="status">
                    <flux:select.option>Aktív</flux:select.option>
                    <flux:select.option>Inaktív</flux:select.option>
                </flux:select>
            </div>
            <div class="w-full max-w-sm min-w-[200px] mt-4">
                <flux:input wire:model="form.vin" :label="__('Alvázszám')" type="text" required
                    autocomplete="form.vin" placeholder="Alvázszám" />
            </div>
        </div>
        <div class="p-6 pt-0">
            <div class="flex space-x-2">
                <flux:button x-show="method_name == 'update_vehicles'"
                    @click.prevent="update_vehicles, [update_vehicles_id]" variant="danger"
                    class="w-full hover:cursor-pointer">
                    {{ __('Delete') }}
                </flux:button>
                <flux:button @click.prevent="method_name" color="green" variant="primary"
                    class="w-full hover:cursor-pointer">
                    {{ __('Mentés') }}
                </flux:button>
            </div>
        </div>
    </div>
</div>

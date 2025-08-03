<div x-data="{ show: false, method_name: '', modal_title: '' }" x-init="$watch('show_update', value => {
    show = value
    method_name = 'update_history_save'
    modal_title = 'Előzmény módosítása'

    if (value) $dispatch('update_history', [update_history_id])
});
$watch('show', value => {
    if (!value) {
        show_update = value
    }
});
window.addEventListener('close_history_modal', () => {
    show = false
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
                <div class="w-full max-w-sm min-w-[200px] mt-4">
                    <flux:input wire:model="form.name" :label="__('Név')" type="text" disabled />
                </div>
            </div>
            <div class="w-full max-w-sm min-w-[200px] mt-4">
                <flux:field>
                    <flux:label>Felvéve</flux:label>
                    <input wire:model="form.pickup_at" type="datetime-local">
                </flux:field>
            </div>
            <div class="w-full max-w-sm min-w-[200px] mt-4">
                <flux:field>
                    <flux:label>Leadva</flux:label>
                    <input wire:model="form.return_at" type="datetime-local">
                </flux:field>
            </div>

            <div class="w-full max-w-sm min-w-[200px] mt-4">
                <flux:input wire:model="form.licence_plate" :label="__('Rendszám')" type="text" disabled />
            </div>

        </div>
        <div class="p-6 pt-0">

            <div class="flex justify-center items-center w-full">
                <x-action-message-success class="me-3" on="history_save_success">
                    {{ __($success_message) }}
                </x-action-message-success>

                @if (!empty($error_message))
                    <flux:text color="red" class="mt-2">{{ $error_message }}</flux:text>
                @endif
            </div>

            <div class="flex space-x-2">
                <div class="flex justify-center items-center h-full w-full">
                    <flux:icon.loading wire:loading />

                    <flux:modal.trigger name="confirm-user-delete">
                        <flux:button wire:loading.remove x-show="method_name == 'update_history_save'" variant="danger"
                            class="w-full hover:cursor-pointer">
                            {{ __('Törlés') }}
                        </flux:button>
                    </flux:modal.trigger>

                    <flux:modal name="confirm-user-delete" class="md:w-96">
                        <div class="space-y-6">
                            <div>
                                <flux:heading size="lg">Biztos törlöd?</flux:heading>
                            </div>
                            <div class="flex">
                                <flux:spacer />
                                <div class="flex justify-center items-center h-full w-full">
                                    <flux:icon.loading wire:loading />
                                </div>
                                <flux:button wire:loading.remove @click.prevent="$wire['delete_history']()"
                                    variant="danger" class="w-full hover:cursor-pointer">
                                    {{ __('Törlés') }}
                                </flux:button>
                            </div>
                        </div>
                    </flux:modal>

                    <flux:button wire:loading.remove @click.prevent="$wire[method_name]()" color="green"
                        variant="primary" class="w-full hover:cursor-pointer">
                        {{ __('Mentés') }}
                    </flux:button>
                </div>
            </div>
        </div>
    </div>
</div>

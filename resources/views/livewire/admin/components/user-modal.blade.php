<div x-data="{ show: false, method_name: '', modal_title: '' }" x-init="$watch('show_new', value => {
    show = value
    method_name = 'create_user'
    modal_title = 'Felhasználó hozzáadása'

    if (value) $dispatch('create_user')
});
$watch('show_update', value => {
    show = value
    method_name = 'update_user'
    modal_title = 'Felhasználó módosítása'

    if (value) $dispatch('update_user', [update_user_id])
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
                <flux:input wire:model="form.name" :label="__('Név')" type="text" required autofocus
                    autocomplete="form.name" placeholder="Név" />
            </div>
            <div class="w-full max-w-sm min-w-[200px] mt-4">
                <flux:input wire:model="username" :label="__('Felhasználónév')" type="text" required
                    autocomplete="username" placeholder="Felhasználónév" />
            </div>
            <div class="w-full max-w-sm min-w-[200px] mt-4">
                <flux:select wire:model="status">
                    <flux:select.option>Aktív</flux:select.option>
                    <flux:select.option>Inaktív</flux:select.option>
                </flux:select>
            </div>
            <div class="w-full max-w-sm min-w-[200px] mt-4">
                <flux:input wire:model="password" :label="__('Jelszó')" type="password" required
                    placeholder="Jelszó" />
            </div>
            <div class="w-full max-w-sm min-w-[200px] mt-4">
                <flux:input wire:model="password_confirm" :label="__('Jelszó megerősítése')" type="password" required
                    placeholder="Jelszó megerősítése" />
            </div>
            <div class="w-full max-w-sm min-w-[200px] max-h-[30vh] mt-4 overflow-y-scroll">
                <flux:checkbox.group wire:model="licence_plates" label="Gépjárművek hozzárendelése">
                    <flux:input wire:model.live="filter_licence_plate" class="mb-2 sticky top-0 dark:bg-neutral-800"
                        type="text" placeholder="Rendszám keresése" />
                    <flux:checkbox value="1" label="MVD141" description="Hyundai Coupe 2004 2.0" />
                    <flux:checkbox value="2" label="UPK502" description="Honda CB500 1996" />
                    <flux:checkbox value="3" label="AAAA125" description="Random autó évszám" />
                    <flux:checkbox value="3" label="AAAA125" description="Random autó évszám" />
                    <flux:checkbox value="3" label="AAAA125" description="Random autó évszám" />
                    <flux:checkbox value="3" label="AAAA125" description="Random autó évszám" />
                    <flux:checkbox value="3" label="AAAA125" description="Random autó évszám" />
                </flux:checkbox.group>
            </div>
        </div>
        <div class="p-6 pt-0">
            <div class="flex space-x-2">
                <flux:button x-show="method_name == 'update_user'" @click.prevent="update_user, [update_user_id]"
                    variant="danger" class="w-full hover:cursor-pointer">
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

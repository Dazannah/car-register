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
});" x-show="show" data-dialog-backdrop="dialog"
    data-dialog-backdrop-close="true"
    class="absolute left-0 top-0 inset-0 z-[999] grid h-screen w-screen place-items-center bg-black/10 backdrop-blur-sm transition-opacity duration-300">
    <div data-dialog="dialog"
        class="relative mx-auto flex w-full max-w-[24rem] flex-col rounded-xl bg-white bg-clip-border text-slate-700 shadow-md">
        <div class="flex flex-col p-6">
            <flux:heading size="xl" x-text="modal_title"></flux:heading>
            <div class="w-full max-w-sm min-w-[200px] mt-4">
                <flux:input wire:model="form.name" :label="__('Név')" type="text" required autofocus
                    autocomplete="form.name" placeholder="Név" />
            </div>
            <div class="w-full max-w-sm min-w-[200px] mt-4">
                <flux:input wire:model="username" :label="__('Felhasználónév')" type="text" required
                    autocomplete="username" placeholder="Felhasználónév" />
            </div>
            <div class="w-full max-w-sm min-w-[200px] mt-4">
                <flux:select wire:model="status" placeholder="Státusz választása">
                    <flux:select.option>Photography</flux:select.option>
                    <flux:select.option>Design services</flux:select.option>
                    <flux:select.option>Web development</flux:select.option>
                    <flux:select.option>Accounting</flux:select.option>
                    <flux:select.option>Legal services</flux:select.option>
                    <flux:select.option>Consulting</flux:select.option>
                    <flux:select.option>Other</flux:select.option>
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

        </div>
        <div class="p-6 pt-0">
            <div class="flex space-x-2">
                <flux:button @click.prevent="show = false" color="red" variant="primary"
                    class="w-full hover:cursor-pointer">
                    {{ __('Mégse') }}
                </flux:button>

                <flux:button @click.prevent="method_name" color="green" variant="primary"
                    class="w-full hover:cursor-pointer">
                    {{ __('Mentés') }}
                </flux:button>
            </div>
        </div>
    </div>
</div>

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
            <h4 class="text-2xl mb-1 font-semibold text-slate-700" x-text="modal_title">
            </h4>
            <div class="w-full max-w-sm min-w-[200px] mt-4">
                <label class="block mb-1 text-sm text-slate-700">
                    Név
                </label>
                <input type="text"
                    class="w-full h-10 bg-transparent placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded px-3 py-2 transition duration-300 ease focus:outline-none focus:border-slate-400 hover:border-slate-400 shadow-sm focus:shadow-md"
                    placeholder="Név" />
            </div>
            <div class="w-full max-w-sm min-w-[200px] mt-4">
                <label class="block mb-1 text-sm text-slate-700">
                    Felhasználónév
                </label>
                <input type="text"
                    class="w-full h-10 bg-transparent placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded px-3 py-2 transition duration-300 ease focus:outline-none focus:border-slate-400 hover:border-slate-400 shadow-sm focus:shadow-md"
                    placeholder="Felhasználónév" />
            </div>
            <div class="w-full max-w-sm min-w-[200px] mt-4">
                <label class="block mb-1 text-sm text-slate-700">
                    Státusz
                </label>
                <input type="select"
                    class="w-full h-10 bg-transparent placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded px-3 py-2 transition duration-300 ease focus:outline-none focus:border-slate-400 hover:border-slate-400 shadow-sm focus:shadow-md"
                    placeholder="Státusz" />
            </div>
            <div class="w-full max-w-sm min-w-[200px] mt-4">
                <label class="block mb-1 text-sm text-slate-700">
                    Jelszó
                </label>
                <input type="password"
                    class="w-full h-10 bg-transparent placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded px-3 py-2 transition duration-300 ease focus:outline-none focus:border-slate-400 hover:border-slate-400 shadow-sm focus:shadow-md"
                    placeholder="Jelszó" />
            </div>
            <div class="w-full max-w-sm min-w-[200px] mt-4">
                <label class="block mb-1 text-sm text-slate-700">
                    Jelszó megerősítése
                </label>
                <input type="text"
                    class="w-full h-10 bg-transparent placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded px-3 py-2 transition duration-300 ease focus:outline-none focus:border-slate-400 hover:border-slate-400 shadow-sm focus:shadow-md"
                    placeholder="Jelszó megerősítése" />
            </div>

        </div>
        <div class="p-6 pt-0">
            <div class="flex space-x-2">
                <button @click.prevent="show = false"
                    class="w-full cursor-pointer mx-auto select-none rounded border border-red-600 py-2 px-4 text-center text-sm font-semibold text-red-600 transition-all hover:bg-red-600 hover:text-white hover:shadow-md hover:shadow-red-600/20 active:bg-red-700 active:text-white active:opacity-[0.85] disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                    type="button" data-dialog-close="true">
                    Mégse
                </button>

                <button
                    class="w-full cursor-pointer mx-auto select-none rounded bg-slate-800 py-2 px-4 text-center text-sm font-semibold text-white shadow-md shadow-slate-900/10 transition-all hover:shadow-lg hover:shadow-slate-900/20 active:opacity-[0.85] disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                    type="button" data-dialog-close="true">
                    Mentés
                </button>
            </div>
        </div>
    </div>
</div>

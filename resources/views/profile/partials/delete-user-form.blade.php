<section class="bg-rose-50/50 backdrop-blur-xl rounded-[2.5rem] border border-rose-100 shadow-xl shadow-rose-200/20 overflow-hidden">
    <div class="p-8 sm:p-10">
        <header class="mb-8">
            <div class="flex items-center gap-3 mb-2">
                <div class="p-2 bg-rose-100 text-rose-600 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m3 6 3 18h12l3-18H3z"/><path d="M19 6V5a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v1h14z"/></svg>
                </div>
                <h2 class="text-xl font-black text-rose-900 tracking-tight">
                    {{ __('Danger Zone') }}
                </h2>
            </div>
            <p class="text-sm text-rose-700 font-medium">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted.') }}
            </p>
        </header>

        <div class="flex flex-col sm:flex-row items-center justify-between gap-6 p-6 bg-white/40 rounded-2xl border border-rose-100/50">
            <div class="max-w-md text-center sm:text-left">
                <p class="text-xs text-rose-800 font-bold leading-relaxed">
                    {{ __('Please download any data or information that you wish to retain before proceeding with the account deletion process.') }}
                </p>
            </div>
            <button
                x-data=""
                x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
                class="w-full sm:w-auto px-8 py-4 bg-rose-600 text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-rose-700 shadow-lg shadow-rose-200 active:scale-95 transition-all outline-none"
            >{{ __('Delete Account') }}</button>
        </div>

        <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
            <form method="post" action="{{ route('profile.destroy') }}" class="p-10">
                @csrf
                @method('delete')

                <h2 class="text-2xl font-black text-slate-900 tracking-tight mb-4">
                    {{ __('Are you sure you want to delete your account?') }}
                </h2>

                <p class="text-sm text-slate-500 font-medium mb-8 leading-relaxed">
                    {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                </p>

                <div class="space-y-4">
                    <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />
                    <x-text-input
                        id="password"
                        name="password"
                        type="password"
                        class="w-full px-5 py-4 bg-slate-50 border-slate-200 rounded-2xl focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500 transition-all font-medium text-slate-700 shadow-none border-0"
                        placeholder="{{ __('Confirm with Password') }}"
                    />
                    <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                </div>

                <div class="mt-10 flex flex-col sm:flex-row justify-end gap-4">
                    <button type="button" x-on:click="$dispatch('close')" class="px-8 py-4 bg-slate-100 text-slate-600 rounded-2xl font-black text-sm uppercase tracking-widest hover:bg-slate-200 transition-all outline-none">
                        {{ __('Cancel') }}
                    </button>

                    <button type="submit" class="px-8 py-4 bg-rose-600 text-white rounded-2xl font-black text-sm uppercase tracking-widest hover:bg-rose-700 shadow-xl shadow-rose-200 active:scale-95 transition-all outline-none">
                        {{ __('Confirm Deletion') }}
                    </button>
                </div>
            </form>
        </x-modal>
    </div>
</section>

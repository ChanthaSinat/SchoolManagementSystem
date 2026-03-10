<section class="bg-white/60 backdrop-blur-xl rounded-[2.5rem] border border-white shadow-xl shadow-slate-200/50 overflow-hidden">
    <div class="p-8 sm:p-10">
        <header class="mb-8">
            <div class="flex items-center gap-3 mb-2">
                <div class="p-2 bg-indigo-100 text-indigo-600 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                </div>
                <h2 class="text-xl font-black text-slate-900 tracking-tight">
                    {{ __('Update Password') }}
                </h2>
            </div>
            <p class="text-sm text-slate-500 font-medium">
                {{ __('Ensure your account is using a long, random password to stay secure.') }}
            </p>
        </header>

        <form method="post" action="{{ route('password.update') }}" class="space-y-8">
            @csrf
            @method('put')

            <div class="space-y-6">
                <!-- Current Password -->
                <div class="space-y-2">
                    <x-input-label for="update_password_current_password" :value="__('Current Password')" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1" />
                    <x-text-input id="update_password_current_password" name="current_password" type="password" class="w-full px-5 py-4 bg-white border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-medium text-slate-700 shadow-sm" autocomplete="current-password" />
                    <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- New Password -->
                    <div class="space-y-2">
                        <x-input-label for="update_password_password" :value="__('New Password')" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1" />
                        <x-text-input id="update_password_password" name="password" type="password" class="w-full px-5 py-4 bg-white border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-medium text-slate-700 shadow-sm" autocomplete="new-password" />
                        <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                    </div>

                    <!-- Confirm Password -->
                    <div class="space-y-2">
                        <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1" />
                        <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="w-full px-5 py-4 bg-white border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-medium text-slate-700 shadow-sm" autocomplete="new-password" />
                        <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-4 pt-4 border-t border-slate-100">
                @if (session('status') === 'password-updated')
                    <p
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition
                        x-init="setTimeout(() => show = false, 2000)"
                        class="text-sm text-emerald-600 font-bold flex items-center gap-2"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                        {{ __('Password Updated') }}
                    </p>
                @endif

                <button type="submit" class="px-8 py-4 bg-slate-900 text-white rounded-2xl font-black text-sm uppercase tracking-widest hover:bg-slate-800 shadow-xl shadow-slate-200 active:scale-95 transition-all flex items-center gap-3">
                    {{ __('Change Password') }}
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                </button>
            </div>
        </form>
    </div>
</section>

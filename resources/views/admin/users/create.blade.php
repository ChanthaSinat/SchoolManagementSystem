<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 tracking-tight">{{ __('Add :role', ['role' => $roleLabel]) }}</h2>
                <p class="text-gray-500 text-sm mt-1">{{ __('Create a new account. You can assign class after saving.') }}</p>
            </div>
            <a href="{{ route($listRoute) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-700">{{ __('Back to :list', ['list' => $listLabel]) }}</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            @if (session('error'))
                <div class="mb-6 rounded-xl bg-red-50 border border-red-200 p-4 text-sm font-medium text-red-800">{{ session('error') }}</div>
            @endif

            <div class="bg-white rounded-2xl shadow border border-gray-100 p-6">
                <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6">
                    @csrf
                    <input type="hidden" name="role" value="{{ $role }}" />

                    <div>
                        <x-input-label for="first_name" :value="__('First name')" />
                        <x-text-input id="first_name" name="first_name" type="text" class="mt-1 block w-full" :value="old('first_name')" required autofocus />
                        <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
                    </div>

                    <div>
                        <x-input-label for="last_name" :value="__('Last name')" />
                        <x-text-input id="last_name" name="last_name" type="text" class="mt-1 block w-full" :value="old('last_name')" required />
                        <x-input-error class="mt-2" :messages="$errors->get('last_name')" />
                    </div>

                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email')" required />
                        <x-input-error class="mt-2" :messages="$errors->get('email')" />
                    </div>

                    <div>
                        <x-input-label for="phone" :value="__('Phone')" />
                        <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone')" />
                        <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                    </div>

                    <div>
                        <x-input-label for="password" :value="__('Password')" />
                        <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" required autocomplete="new-password" />
                        <x-input-error class="mt-2" :messages="$errors->get('password')" />
                    </div>

                    <div>
                        <x-input-label for="password_confirmation" :value="__('Confirm password')" />
                        <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" required autocomplete="new-password" />
                        <x-input-error class="mt-2" :messages="$errors->get('password_confirmation')" />
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                        <x-primary-button>{{ __('Create :role', ['role' => $roleLabel]) }}</x-primary-button>
                        <a href="{{ route($listRoute) }}" class="text-sm text-gray-600 hover:text-gray-800">{{ __('Cancel') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

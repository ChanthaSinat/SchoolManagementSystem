<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 tracking-tight">{{ __('Edit :role', ['role' => $roleLabel]) }}</h2>
                <p class="text-gray-500 text-sm mt-1">{{ $user->full_name ?: $user->name }}</p>
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
                <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label for="first_name" :value="__('First name')" />
                        <x-text-input id="first_name" name="first_name" type="text" class="mt-1 block w-full" :value="old('first_name', $user->first_name)" required autofocus />
                        <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
                    </div>

                    <div>
                        <x-input-label for="last_name" :value="__('Last name')" />
                        <x-text-input id="last_name" name="last_name" type="text" class="mt-1 block w-full" :value="old('last_name', $user->last_name)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('last_name')" />
                    </div>

                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('email')" />
                    </div>

                    <div>
                        <x-input-label for="phone" :value="__('Phone')" />
                        <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $user->phone)" />
                        <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                    </div>

                    <div>
                        <x-input-label for="password" :value="__('New password (leave blank to keep current)')" />
                        <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                        <x-input-error class="mt-2" :messages="$errors->get('password')" />
                    </div>

                    <div>
                        <x-input-label for="password_confirmation" :value="__('Confirm password')" />
                        <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                        <x-primary-button>{{ __('Save changes') }}</x-primary-button>
                        <a href="{{ route($listRoute) }}" class="text-sm text-gray-600 hover:text-gray-800">{{ __('Cancel') }}</a>
                    </div>
                </form>

                <div class="mt-8 pt-6 border-t border-gray-200">
                    <p class="text-sm text-gray-600 mb-2">{{ __('Remove this user permanently?') }}</p>
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('This cannot be undone. Remove this user?') }}');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-sm text-red-600 hover:text-red-800 font-medium">{{ __('Remove user') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

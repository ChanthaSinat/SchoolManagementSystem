<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $class->name }}
            </h2>
            <a href="{{ route('classes.edit', $class) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                {{ __('Edit') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Academic Year') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $class->academicYear->name ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Name') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $class->name }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-3">{{ __('Sections') }}</h3>
                    @if ($class->sections->isNotEmpty())
                        <ul class="list-disc list-inside">
                            @foreach ($class->sections as $section)
                                <li>{{ $section->name }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-500">{{ __('No sections linked.') }}</p>
                    @endif
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-3">{{ __('Subjects') }}</h3>
                    @if ($class->subjects->isNotEmpty())
                        <ul class="list-disc list-inside">
                            @foreach ($class->subjects as $subject)
                                <li>{{ $subject->name }} @if($subject->code)({{ $subject->code }})@endif</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-500">{{ __('No subjects assigned.') }}</p>
                    @endif
                </div>
            </div>

            <div>
                <a href="{{ route('classes.index') }}" class="text-indigo-600 hover:text-indigo-900">{{ __('Back to list') }}</a>
            </div>
        </div>
    </div>
</x-app-layout>

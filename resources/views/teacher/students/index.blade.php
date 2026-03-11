@extends('layouts.teacher-app')

@section('content')
<div class="flex items-end justify-between mb-8">
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-slate-900 tracking-tight">{{ __('My Students') }}</h1>
        <p class="text-slate-600 mt-1.5 font-medium text-sm">{{ __('Students enrolled in your classes.') }}</p>
    </div>
</div>

<div class="bg-white/90 backdrop-blur rounded-2xl shadow-lg border border-slate-200/80 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-slate-100/90 border-b border-slate-200/80">
                <tr>
                    <th class="px-6 py-4 text-xs font-bold text-slate-600 uppercase tracking-wider">{{ __('Name') }}</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-600 uppercase tracking-wider">{{ __('Class') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200/80">
                @forelse ($students as $row)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4">
                            @php $fullName = trim($row->user->full_name); @endphp
                            <span class="font-semibold text-slate-800">{{ $fullName ?: ($row->user->name ?? __('—')) }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600">{{ $row->class_names ?? '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="px-6 py-12 text-center text-slate-600 font-medium text-sm">
                            {{ __('No students found in your classes.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

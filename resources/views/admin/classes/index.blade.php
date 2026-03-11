@extends('layouts.admin-app')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Classes / Sections</h1>
            <p class="text-slate-500 text-sm font-medium mt-1">Manage classes with school type, year level, and semester.</p>
        </div>
        <a href="{{ route('admin.classes.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-xs font-black rounded-xl shadow-sm hover:bg-indigo-700 transition">
            + Add Class
        </a>
    </div>

    @if (session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white/70 backdrop-blur-xl rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-slate-100">
            <thead class="bg-slate-50/60">
                <tr>
                    <th class="px-6 py-3 text-left text-[10px] font-black text-slate-500 uppercase tracking-widest">Class</th>
                    <th class="px-6 py-3 text-left text-[10px] font-black text-slate-500 uppercase tracking-widest">School Type</th>
                    <th class="px-6 py-3 text-left text-[10px] font-black text-slate-500 uppercase tracking-widest">Year Level</th>
                    <th class="px-6 py-3 text-left text-[10px] font-black text-slate-500 uppercase tracking-widest">Academic Year</th>
                    <th class="px-6 py-3 text-left text-[10px] font-black text-slate-500 uppercase tracking-widest">Semester</th>
                    <th class="px-6 py-3 text-right text-[10px] font-black text-slate-500 uppercase tracking-widest">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 bg-white/40">
                @forelse ($classes as $class)
                    <tr>
                        <td class="px-6 py-3 text-sm font-semibold text-slate-800">
                            {{ $class->name }}
                            @if ($class->section)
                                <span class="text-slate-400 text-xs ml-1">({{ $class->section }})</span>
                            @endif
                        </td>
                        <td class="px-6 py-3 text-sm text-slate-500">
                            {{ $class->schoolType?->name ?? '—' }}
                        </td>
                        <td class="px-6 py-3 text-sm text-slate-500">
                            {{ $class->year_level ?? '—' }}
                        </td>
                        <td class="px-6 py-3 text-sm text-slate-500">
                            {{ $class->academicYear?->name ?? '—' }}
                        </td>
                        <td class="px-6 py-3 text-sm text-slate-500">
                            {{ $class->semester?->name ?? '—' }}
                        </td>
                        <td class="px-6 py-3 text-right text-sm">
                            <a href="{{ route('admin.classes.edit', $class) }}" class="text-indigo-600 font-semibold hover:text-indigo-800 mr-3">Manage</a>
                            <form action="{{ route('admin.classes.destroy', $class) }}" method="POST" class="inline" onsubmit="return confirm('Delete this class and its assignments?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-500 font-semibold hover:text-rose-700">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-5 text-center text-sm text-slate-500">
                            No classes found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="px-6 py-4 border-t border-slate-100">
            {{ $classes->links() }}
        </div>
    </div>
</div>
@endsection


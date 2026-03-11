@extends('layouts.admin-app')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Semesters / Terms</h1>
            <p class="text-slate-500 text-sm font-medium mt-1">Manage terms for each academic year.</p>
        </div>
        <a href="{{ route('admin.semesters.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-xs font-black rounded-xl shadow-sm hover:bg-indigo-700 transition">
            + Add Semester
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
                    <th class="px-6 py-3 text-left text-[10px] font-black text-slate-500 uppercase tracking-widest">Name</th>
                    <th class="px-6 py-3 text-left text-[10px] font-black text-slate-500 uppercase tracking-widest">Academic Year</th>
                    <th class="px-6 py-3 text-left text-[10px] font-black text-slate-500 uppercase tracking-widest">Code</th>
                    <th class="px-6 py-3 text-left text-[10px] font-black text-slate-500 uppercase tracking-widest">Order</th>
                    <th class="px-6 py-3 text-left text-[10px] font-black text-slate-500 uppercase tracking-widest">Status</th>
                    <th class="px-6 py-3 text-right text-[10px] font-black text-slate-500 uppercase tracking-widest">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 bg-white/40">
                @forelse ($semesters as $semester)
                    <tr>
                        <td class="px-6 py-3 text-sm font-semibold text-slate-800">
                            {{ $semester->name }}
                        </td>
                        <td class="px-6 py-3 text-sm text-slate-500">
                            {{ $semester->academicYear?->name ?? '—' }}
                        </td>
                        <td class="px-6 py-3 text-sm text-slate-500 font-mono">
                            {{ $semester->code ?? '—' }}
                        </td>
                        <td class="px-6 py-3 text-sm text-slate-500">
                            {{ $semester->order ?? '—' }}
                        </td>
                        <td class="px-6 py-3 text-sm">
                            @if ($semester->is_active)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-black bg-emerald-50 text-emerald-700 border border-emerald-100 uppercase tracking-wider">
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-black bg-slate-100 text-slate-500 border border-slate-200 uppercase tracking-wider">
                                    Inactive
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-3 text-right text-sm">
                            <a href="{{ route('admin.semesters.edit', $semester) }}" class="text-indigo-600 font-semibold hover:text-indigo-800 mr-3">Edit</a>
                            <form action="{{ route('admin.semesters.destroy', $semester) }}" method="POST" class="inline" onsubmit="return confirm('Delete this semester? Classes and subjects using it may need updating.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-500 font-semibold hover:text-rose-700">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-5 text-center text-sm text-slate-500">
                            No semesters defined yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection


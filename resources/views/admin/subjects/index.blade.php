@extends('layouts.admin-app')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Subjects</h1>
            <p class="text-slate-500 text-sm font-medium mt-1">Manage your subject catalog and curriculum mapping.</p>
        </div>
        <a href="{{ route('admin.subjects.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-xs font-black rounded-xl shadow-sm hover:bg-indigo-700 transition">
            + Add Subject
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
                    <th class="px-6 py-3 text-left text-[10px] font-black text-slate-500 uppercase tracking-widest">Subject</th>
                    <th class="px-6 py-3 text-left text-[10px] font-black text-slate-500 uppercase tracking-widest">Code</th>
                    <th class="px-6 py-3 text-right text-[10px] font-black text-slate-500 uppercase tracking-widest">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 bg-white/40">
                @forelse ($subjects as $subject)
                    <tr>
                        <td class="px-6 py-3 text-sm font-semibold text-slate-800">
                            {{ $subject->name }}
                        </td>
                        <td class="px-6 py-3 text-sm text-slate-500 font-mono">
                            {{ $subject->code ?? '—' }}
                        </td>
                        <td class="px-6 py-3 text-right text-sm">
                            <a href="{{ route('admin.subjects.edit', $subject) }}" class="text-indigo-600 font-semibold hover:text-indigo-800 mr-3">Edit</a>
                            <form action="{{ route('admin.subjects.destroy', $subject) }}" method="POST" class="inline" onsubmit="return confirm('Delete this subject? It will be removed from any classes using it.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-500 font-semibold hover:text-rose-700">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-5 text-center text-sm text-slate-500">
                            No subjects found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="px-6 py-4 border-t border-slate-100">
            {{ $subjects->links() }}
        </div>
    </div>
</div>
@endsection


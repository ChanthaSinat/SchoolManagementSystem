<div class="relative w-full h-48 sm:h-64 rounded-[2.5rem] overflow-hidden bg-slate-900 shadow-2xl group">
    <!-- Background Pattern/Gradient -->
    <div class="absolute inset-0 bg-gradient-to-br from-indigo-600/80 via-violet-600/50 to-emerald-500/30 backdrop-blur-3xl transition-transform duration-1000 group-hover:scale-110"></div>
    <div class="absolute inset-0 opacity-20" style="background-image: url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23ffffff\" fill-opacity=\"0.4\"%3E%3Cpath d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    
    <!-- User Avatar & Info Positioning -->
    <div class="absolute -bottom-1 inset-x-0 p-8 flex flex-col sm:flex-row items-center sm:items-end gap-6 bg-gradient-to-t from-slate-950/80 to-transparent pt-20">
        <div class="relative">
            <div class="w-24 h-24 sm:w-32 sm:h-32 rounded-[2rem] bg-white p-1.5 shadow-2xl relative z-10 transition-transform duration-500 group-hover:rotate-3 group-hover:scale-105">
                <div class="w-full h-full rounded-[1.75rem] bg-indigo-500 flex items-center justify-center text-white text-3xl sm:text-4xl font-black shadow-inner">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
            </div>
            <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-emerald-500 rounded-full border-4 border-white z-20 flex items-center justify-center text-white shadow-lg animate-bounce">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
            </div>
        </div>
        
        <div class="text-center sm:text-left pb-2 flex-1">
            <h1 class="text-2xl sm:text-4xl font-black text-white tracking-tight mb-2 uppercase">{{ auth()->user()->name }}</h1>
            <div class="flex flex-wrap items-center justify-center sm:justify-start gap-3">
                <span class="px-4 py-1.5 bg-white/20 backdrop-blur-md rounded-xl text-[10px] font-black text-white uppercase tracking-widest border border-white/20">
                    {{ auth()->user()->role === 'admin' ? 'Administrator' : (auth()->user()->role === 'teacher' ? 'Faculty Member' : 'Student Account') }}
                </span>
                <span class="px-3 py-1 bg-emerald-500/20 rounded-lg text-[8px] font-black text-emerald-300 uppercase tracking-widest flex items-center gap-1.5 border border-emerald-500/30">
                    <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse"></span>
                    ACTIVE VERIFIED
                </span>
            </div>
        </div>
    </div>
</div>

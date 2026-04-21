<div id="sidebar-overlay" class="fixed inset-0 z-40 hidden bg-slate-900/60 backdrop-blur-sm transition-opacity md:hidden" onclick="toggleSidebar()"></div>

<aside id="sidebar" class="fixed left-0 top-0 z-50 flex h-screen w-72 flex-col bg-primary text-white transition-all duration-300 ease-in-out md:static md:translate-x-0 -translate-x-full shadow-2xl">

    <div class="flex items-center justify-between px-6 py-6 border-b border-white/10">
        <a href="{{ route('kepsek.dashboard') }}" class="flex items-center gap-3 group">
            <div class="bg-white/10 p-2 rounded-xl group-hover:scale-110 transition-transform duration-300">
                <i class="fa-solid fa-graduation-cap text-2xl text-white"></i>
            </div>
            <div class="flex flex-col">
                <span class="text-xl font-bold tracking-tight text-white">SPP Digital</span>
                <span class="text-[10px] text-white/60 uppercase tracking-[2px] -mt-1">Executive System</span>
            </div>
        </a>

        <button onclick="toggleSidebar()" class="md:hidden text-white/70 hover:text-white p-1">
            <i class="fa-solid fa-arrow-left-long text-xl"></i>
        </button>
    </div>

    <div class="flex-1 overflow-y-auto custom-scrollbar px-4 py-6">
        <nav class="space-y-8">
            <div>
                <h3 class="px-4 mb-3 text-[11px] font-bold uppercase tracking-[2px] text-white/40">Executive Center</h3>
                <ul class="space-y-1.5">
                    <li>
                        <a href="{{ route('kepsek.dashboard') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 transition-all duration-200 {{ request()->routeIs('kepsek.dashboard') ? 'bg-white text-primary font-bold shadow-lg shadow-black/10' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                            <i class="fa-solid fa-chart-line text-lg"></i>
                            <span class="text-sm">Dashboard Performa</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('kepsek.laporan.index') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 transition-all duration-200 {{ request()->routeIs('kepsek.laporan.*') ? 'bg-white text-primary font-bold shadow-lg shadow-black/10' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                            <i class="fa-solid fa-file-invoice-dollar text-lg"></i>
                            <span class="text-sm">Laporan Keuangan</span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>

    <div class="p-4 border-t border-white/10">
        <div class="flex items-center gap-3 px-2 py-3 bg-white/5 rounded-2xl">
            <div class="h-10 w-10 rounded-full bg-white/10 flex items-center justify-center font-bold text-sm text-white">
                {{ substr(auth()->user()->name, 0, 1) }}
            </div>
            <div class="flex flex-col overflow-hidden">
                <span class="text-xs font-bold truncate text-white">{{ auth()->user()->name }}</span>
                <span class="text-[10px] text-white/50 truncate uppercase tracking-wider">{{ auth()->user()->role }}</span>
            </div>
        </div>
    </div>
</aside>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.1); border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(255, 255, 255, 0.2); }
</style>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
    }
</script>
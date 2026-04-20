<aside class="hidden md:flex flex-col w-64 bg-primary text-white sticky top-0 h-screen overflow-y-auto">
    <div class="p-6 font-bold text-2xl tracking-widest border-b border-red-900 flex items-center gap-2">
        <i class="fa-solid fa-wallet text-yellow-500"></i>
        <span>SPP <span class="text-yellow-500">PAY</span></span>
    </div>

    <nav class="flex-1 p-4 space-y-2">
        <div class="text-xs text-red-300 font-semibold mb-2 mt-4 px-3 uppercase tracking-wider">Monitoring Eksekutif</div>

        <a href="/kepsek/dashboard" class="flex items-center gap-3 p-3 rounded-xl hover:bg-red-900 transition {{ Request::is('kepsek/dashboard') ? 'bg-red-900 shadow-md font-semibold' : '' }}">
            <i class="fa-solid fa-chart-pie w-5 text-center"></i> Dashboard
        </a>
        
        <a href="/kepsek/laporan" class="flex items-center gap-3 p-3 rounded-xl hover:bg-red-900 transition {{ Request::is('kepsek/laporan*') ? 'bg-red-900 shadow-md font-semibold' : '' }}">
            <i class="fa-solid fa-chart-bar w-5 text-center"></i> Laporan Keuangan
        </a>
    </nav>
</aside>
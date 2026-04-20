<aside class="hidden md:flex flex-col w-64 bg-primary text-white sticky top-0 h-screen overflow-y-auto">
    <div class="p-6 font-bold text-2xl tracking-widest border-b border-red-900 flex items-center gap-2">
        <i class="fa-solid fa-wallet text-yellow-500"></i>
        <span>SPP <span class="text-yellow-500">PAY</span></span>
    </div>

    <nav class="flex-1 p-4 space-y-1.5 text-sm">
        <div class="text-xs text-red-300 font-semibold mb-2 mt-4 px-3 uppercase tracking-wider">Menu Utama</div>
        
        <a href="/admin/dashboard" class="flex items-center gap-3 p-3 rounded-xl hover:bg-red-900 transition {{ Request::is('admin/dashboard') ? 'bg-red-900 shadow-md font-semibold' : '' }}">
            <i class="fa-solid fa-chart-line w-5 text-center"></i> Dashboard
        </a>
        
        <a href="/admin/siswa" class="flex items-center gap-3 p-3 rounded-xl hover:bg-red-900 transition {{ Request::is('admin/siswa*') ? 'bg-red-900 shadow-md font-semibold' : '' }}">
            <i class="fa-solid fa-user-graduate w-5 text-center"></i> Manajemen Siswa
        </a>

        <div class="text-xs text-red-300 font-semibold mb-2 mt-6 px-3 uppercase tracking-wider">Transaksi & Tagihan</div>
        
        <a href="/admin/tagihan" class="flex items-center gap-3 p-3 rounded-xl hover:bg-red-900 transition {{ Request::is('admin/tagihan*') ? 'bg-red-900 shadow-md font-semibold' : '' }}">
            <i class="fa-solid fa-file-invoice-dollar w-5 text-center"></i> Manajemen Tagihan
        </a>
        
        <a href="/admin/pembayaran" class="flex items-center gap-3 p-3 rounded-xl hover:bg-red-900 transition {{ Request::is('admin/pembayaran*') ? 'bg-red-900 shadow-md font-semibold' : '' }}">
            <i class="fa-solid fa-credit-card w-5 text-center"></i> Virtual Account / Pay
        </a>
        
        <a href="/admin/riwayat" class="flex items-center gap-3 p-3 rounded-xl hover:bg-red-900 transition {{ Request::is('admin/riwayat*') ? 'bg-red-900 shadow-md font-semibold' : '' }}">
            <i class="fa-solid fa-clock-rotate-left w-5 text-center"></i> Riwayat Transaksi
        </a>

        <div class="text-xs text-red-300 font-semibold mb-2 mt-6 px-3 uppercase tracking-wider">Laporan & Sistem</div>

        <a href="/admin/laporan" class="flex items-center gap-3 p-3 rounded-xl hover:bg-red-900 transition {{ Request::is('admin/laporan*') ? 'bg-red-900 shadow-md font-semibold' : '' }}">
            <i class="fa-solid fa-file-pdf w-5 text-center"></i> Laporan
        </a>

        <a href="/admin/notifikasi" class="flex items-center gap-3 p-3 rounded-xl hover:bg-red-900 transition {{ Request::is('admin/notifikasi*') ? 'bg-red-900 shadow-md font-semibold' : '' }}">
            <i class="fa-solid fa-bell w-5 text-center"></i> Notifikasi
        </a>

        <a href="/admin/pengaturan" class="flex items-center gap-3 p-3 rounded-xl hover:bg-red-900 transition {{ Request::is('admin/pengaturan*') ? 'bg-red-900 shadow-md font-semibold' : '' }}">
            <i class="fa-solid fa-gear w-5 text-center"></i> Pengaturan
        </a>
    </nav>
</aside>
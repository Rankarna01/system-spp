<nav class="fixed bottom-0 left-0 right-0 bg-surface border-t border-gray-200 flex justify-between items-center py-2 px-6 z-40 md:hidden shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] pb-safe">
    
    <a href="/siswa/dashboard" class="flex flex-col items-center gap-1 min-w-[50px] {{ Request::is('siswa/dashboard') ? 'text-primary' : 'text-gray-400 hover:text-gray-600' }}">
        <i class="fa-solid fa-house text-xl {{ Request::is('siswa/dashboard') ? 'mb-0.5' : '' }}"></i>
        <span class="text-[10px] font-medium">Beranda</span>
    </a>
    
    <a href="/siswa/tagihan" class="flex flex-col items-center gap-1 min-w-[50px] {{ Request::is('siswa/tagihan*') ? 'text-primary' : 'text-gray-400 hover:text-gray-600' }}">
        <i class="fa-solid fa-file-invoice-dollar text-xl {{ Request::is('siswa/tagihan*') ? 'mb-0.5' : '' }}"></i>
        <span class="text-[10px] font-medium">Tagihan</span>
    </a>

    <a href="/siswa/riwayat" class="flex flex-col items-center gap-1 min-w-[50px] {{ Request::is('siswa/riwayat*') ? 'text-primary' : 'text-gray-400 hover:text-gray-600' }}">
        <i class="fa-solid fa-receipt text-xl {{ Request::is('siswa/riwayat*') ? 'mb-0.5' : '' }}"></i>
        <span class="text-[10px] font-medium">Riwayat</span>
    </a>

    <a href="/siswa/notifikasi" class="flex flex-col items-center gap-1 min-w-[50px] relative {{ Request::is('siswa/notifikasi*') ? 'text-primary' : 'text-gray-400 hover:text-gray-600' }}">
        <span class="absolute -top-1 right-2 bg-red-500 text-white text-[8px] font-bold px-1.5 py-0.5 rounded-full">2</span>
        <i class="fa-solid fa-bell text-xl {{ Request::is('siswa/notifikasi*') ? 'mb-0.5' : '' }}"></i>
        <span class="text-[10px] font-medium">Notif</span>
    </a>

</nav>

<style>
    @supports (padding-bottom: env(safe-area-inset-bottom)) {
        .pb-safe {
            padding-bottom: env(safe-area-inset-bottom);
        }
    }
</style>
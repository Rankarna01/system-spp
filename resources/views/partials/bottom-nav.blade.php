<div class="md:hidden fixed bottom-0 left-0 right-0 w-full bg-surface border-t border-gray-200 shadow-[0_-10px_20px_rgba(0,0,0,0.03)] z-50 rounded-t-2xl pb-safe">
    <div class="flex justify-around items-center h-[72px] px-2 max-w-md mx-auto">
        
        <a href="{{ route('siswa.dashboard') }}" class="flex flex-col items-center justify-center w-full text-gray-400 hover:text-primary transition-colors {{ request()->routeIs('siswa.dashboard') ? 'text-primary' : '' }}">
            <i class="fa-solid fa-house text-xl mb-1"></i>
            <span class="text-[10px] font-bold tracking-wide">Home</span>
        </a>

        <a href="{{ route('siswa.tagihan.index') }}" class="flex flex-col items-center justify-center w-full text-gray-400 hover:text-primary transition-colors {{ request()->routeIs('siswa.tagihan.*') ? 'text-primary' : '' }}">
            <i class="fa-solid fa-file-invoice-dollar text-xl mb-1"></i>
            <span class="text-[10px] font-bold tracking-wide">Tagihan</span>
        </a>

        <a href="{{ route('siswa.riwayat.index') }}" class="flex flex-col items-center justify-center w-full text-gray-400 hover:text-primary transition-colors {{ request()->routeIs('siswa.riwayat.*') ? 'text-primary' : '' }}">
            <i class="fa-solid fa-clock-rotate-left text-xl mb-1"></i>
            <span class="text-[10px] font-bold tracking-wide">Riwayat</span>
        </a>

        <a href="{{ route('siswa.notifikasi.index') }}" class="flex flex-col items-center justify-center w-full text-gray-400 hover:text-primary transition-colors relative {{ request()->routeIs('siswa.notifikasi.*') ? 'text-primary' : '' }}">
            <span class="absolute top-1 right-3 w-2 h-2 bg-red-500 rounded-full border-2 border-surface"></span>
            <i class="fa-solid fa-bell text-xl mb-1"></i>
            <span class="text-[10px] font-bold tracking-wide">Notif</span>
        </a>

        <a href="{{ route('siswa.profil.index') }}" class="flex flex-col items-center justify-center w-full text-gray-400 hover:text-primary transition-colors {{ request()->routeIs('siswa.profil.*') ? 'text-primary' : '' }}">
            <i class="fa-solid fa-user text-xl mb-1"></i>
            <span class="text-[10px] font-bold tracking-wide">Profil</span>
        </a>

    </div>
</div>
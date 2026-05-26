<div id="sidebar-overlay" class="fixed inset-0 z-40 hidden bg-slate-900/60 backdrop-blur-sm transition-opacity md:hidden" onclick="toggleSidebar()"></div>

<aside id="sidebar" class="fixed left-0 top-0 z-50 flex h-screen w-72 flex-col bg-primary text-white transition-all duration-300 ease-in-out md:static md:translate-x-0 -translate-x-full shadow-2xl">

   <div class="flex items-center justify-between px-6 py-6 border-b border-white/10">
    <a href="{{ auth()->user()->role == 'admin' ? route('admin.dashboard') : route('kepsek.dashboard') }}" class="flex items-center gap-3 group">
        
        <div class="bg-white/10 p-2 rounded-xl group-hover:scale-110 transition-transform duration-300 flex items-center justify-center shrink-0 text-white">
            @if(isset($gSetting) && $gSetting->logo)
                <img src="{{ asset('storage/' . $gSetting->logo) }}" class="h-6 w-6 object-contain rounded">
            @else
                <i class="fa-solid fa-graduation-cap text-2xl"></i>
            @endif
        </div>
        
        <div class="flex flex-col overflow-hidden">
            <span class="text-xl font-bold tracking-tight text-white truncate">{{ $gSetting->nama_sistem ?? 'SPP Digital' }}</span>
            <span class="text-[10px] text-white/60 uppercase tracking-[2px] -mt-1 truncate">{{ $gSetting->slogan_sistem ?? 'Management System' }}</span>
        </div>
    </a>

    <button onclick="toggleSidebar()" class="md:hidden text-white/70 hover:text-white p-1">
        <i class="fa-solid fa-arrow-left-long text-xl"></i>
    </button>
</div>

    <div class="flex-1 overflow-y-auto custom-scrollbar px-4 py-6">
        <nav class="space-y-8">
            
            @if (auth()->user()->role == 'admin')
                <div>
                    <ul class="space-y-1.5">
                        <li>
                            <a href="{{ route('admin.dashboard') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-white text-primary font-bold shadow-lg shadow-black/10' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                                <i class="fa-solid fa-house-chimney text-lg"></i>
                                <span class="text-sm">Dashboard Overview</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div>
                    <h3 class="px-4 mb-3 text-[11px] font-bold uppercase tracking-[2px] text-white/40">Akademik & Siswa</h3>
                    <ul class="space-y-1.5">
                        <li>
                            <button onclick="toggleDropdown('dropdown-siswa', 'icon-siswa')" class="group flex w-full items-center gap-3 rounded-xl px-4 py-3 text-white/80 transition-all duration-200 hover:bg-white/10 hover:text-white focus:outline-none {{ request()->routeIs('admin.siswa.*', 'admin.jurusan.*', 'admin.kelas.*', 'admin.tahun.*', 'admin.akun.*') ? 'bg-white/5 text-white' : '' }}">
                                <div class="flex items-center gap-3 flex-1">
                                    <i class="fa-solid fa-user-graduate text-lg"></i>
                                    <span class="text-sm">Manajemen Siswa</span>
                                </div>
                                <i id="icon-siswa" class="fa-solid fa-chevron-right text-[10px] transition-transform duration-300 {{ request()->routeIs('admin.siswa.*', 'admin.jurusan.*', 'admin.kelas.*', 'admin.tahun.*', 'admin.akun.*') ? 'rotate-90' : '' }}"></i>
                            </button>

                            <div id="dropdown-siswa" class="{{ request()->routeIs('admin.siswa.*', 'admin.jurusan.*', 'admin.kelas.*', 'admin.tahun.*', 'admin.akun.*') ? 'flex' : 'hidden' }} flex-col mt-1 ml-4 border-l-2 border-white/10 pl-2 space-y-1 overflow-hidden transition-all duration-300">
                                <a href="{{ route('admin.siswa.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2.5 text-xs transition-all {{ request()->routeIs('admin.siswa.*') ? 'text-white font-semibold' : 'text-white/60 hover:text-white hover:bg-white/5' }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current"></span> Data Siswa
                                </a>
                                <a href="{{ route('admin.akun.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2.5 text-xs transition-all {{ request()->routeIs('admin.akun.*') ? 'text-white font-semibold' : 'text-white/60 hover:text-white hover:bg-white/5' }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current"></span> Akun Login Siswa
                                </a>
                                <a href="{{ route('admin.jurusan.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2.5 text-xs transition-all {{ request()->routeIs('admin.jurusan.*') ? 'text-white font-semibold' : 'text-white/60 hover:text-white hover:bg-white/5' }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current"></span> Master Jurusan
                                </a>
                                <a href="{{ route('admin.kelas.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2.5 text-xs transition-all {{ request()->routeIs('admin.kelas.*') ? 'text-white font-semibold' : 'text-white/60 hover:text-white hover:bg-white/5' }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current"></span> Master Kelas
                                </a>
                                <a href="{{ route('admin.tahun.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2.5 text-xs transition-all {{ request()->routeIs('admin.tahun.*') ? 'text-white font-semibold' : 'text-white/60 hover:text-white hover:bg-white/5' }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current"></span> Tahun Ajaran
                                </a>
                            </div>
                        </li>

                        <li>
                            <a href="{{ route('admin.tagihan.index') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 transition-all duration-200 {{ request()->routeIs('admin.tagihan.*') ? 'bg-white text-primary font-bold shadow-lg shadow-black/10' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                                <i class="fa-solid fa-file-invoice-dollar text-lg"></i>
                                <span class="text-sm">Tagihan SPP</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.pembayaran.index') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 transition-all duration-200 {{ request()->routeIs('admin.pembayaran.*') ? 'bg-white text-primary font-bold shadow-lg shadow-black/10' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                                <i class="fa-solid fa-wallet text-lg"></i>
                                <span class="text-sm">VA & Pembayaran</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div>
                    <h3 class="px-4 mb-3 text-[11px] font-bold uppercase tracking-[2px] text-white/40">System Control</h3>
                    <ul class="space-y-1.5">
                        <li>
                            <a href="{{ route('admin.riwayat.index') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 transition-all duration-200 {{ request()->routeIs('admin.riwayat.*') ? 'bg-white text-primary font-bold shadow-lg shadow-black/10' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                                <i class="fa-solid fa-clock-rotate-left text-lg"></i>
                                <span class="text-sm">Riwayat Transaksi</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.laporan.index') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 transition-all duration-200 {{ request()->routeIs('admin.laporan.*') ? 'bg-white text-primary font-bold shadow-lg shadow-black/10' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                                <i class="fa-solid fa-chart-simple text-lg"></i>
                                <span class="text-sm">Laporan Transaksi</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.notifikasi.index') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 transition-all duration-200 {{ request()->routeIs('admin.notifikasi.*') ? 'bg-white text-primary font-bold shadow-lg shadow-black/10' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                                <i class="fa-solid fa-sliders text-lg"></i>
                                <span class="text-sm">Pengaturan</span>
                            </a>
                        </li>
                        <li>
    <a href="{{ route('admin.pengaturan.index') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 transition-all duration-200 {{ request()->routeIs('admin.pengaturan.*') ? 'bg-white text-primary font-bold shadow-lg shadow-black/10' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
        <i class="fa-solid fa-gear text-lg"></i>
        <span class="text-sm">Pengaturan Sistem</span>
    </a>
</li>
                    </ul>
                </div>
            @endif

            {{-- @if(auth()->user()->role == 'kepsek')
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
            @endif --}}

        </nav>
    </div>

    <div class="p-4 border-t border-white/10">
        <div class="flex items-center gap-3 px-2 py-3 bg-white/5 rounded-2xl">
            <div class="h-10 w-10 rounded-full bg-white/10 flex items-center justify-center font-bold text-sm">
                {{ substr(auth()->user()->name, 0, 1) }}
            </div>
            <div class="flex flex-col overflow-hidden">
                <span class="text-xs font-bold truncate">{{ auth()->user()->name }}</span>
                <span class="text-[10px] text-white/50 truncate uppercase tracking-wider">{{ auth()->user()->role }}</span>
            </div>
        </div>
    </div>
</aside>

<style>
    /* Merapikan scrollbar untuk tampilan modern */
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.1); border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(255, 255, 255, 0.2); }
</style>

<script>
    function toggleDropdown(dropdownId, iconId) {
        const dropdown = document.getElementById(dropdownId);
        const icon = document.getElementById(iconId);
        dropdown.classList.toggle('hidden');
        dropdown.classList.toggle('flex');
        icon.classList.toggle('rotate-90');
    }

    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
    }

    // Mendeteksi otomatis state aktif saat load
    document.addEventListener("DOMContentLoaded", function() {
        const activeItem = document.querySelector('#dropdown-siswa .text-white');
        if (activeItem) {
            const dropdown = document.getElementById('dropdown-siswa');
            const icon = document.getElementById('icon-siswa');
            if(dropdown && icon) {
                dropdown.classList.remove('hidden');
                dropdown.classList.add('flex');
                icon.classList.add('rotate-90');
            }
        }
    });
</script>
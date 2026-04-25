<header class="sticky top-0 z-40 flex w-full bg-surface drop-shadow-sm">
    <div class="flex flex-grow items-center justify-between px-4 py-4 md:px-6 2xl:px-11">
        
        <div class="flex items-center gap-2 sm:gap-4 md:hidden">
            <button onclick="toggleSidebar()" class="z-40 block rounded-md border border-gray-200 bg-surface p-2 shadow-sm text-primary hover:bg-gray-50 transition">
                <i class="fa-solid fa-bars text-xl"></i>
            </button>
            <span class="font-bold text-primary text-lg md:hidden ml-2">SPP Digital</span>
        </div>

        <div class="hidden md:block">
            <h2 class="text-xl font-semibold text-gray-800">@yield('title', 'Dashboard')</h2>
        </div>

        <div class="flex items-center gap-4">
            <div class="relative" id="profile-dropdown-container">
                <button id="profile-btn" class="flex items-center gap-3 focus:outline-none">
                    <div class="text-right hidden sm:block">
                        <span class="block text-sm font-medium text-black">{{ auth()->user()->name ?? 'Pengguna' }}</span>
                        <span class="block text-xs text-gray-500 capitalize">{{ auth()->user()->role ?? 'Role' }}</span>
                    </div>
                    <div class="h-10 w-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary font-bold border border-primary/20 hover:bg-primary hover:text-white transition-colors duration-300">
                        <i class="fa-regular fa-user"></i>
                    </div>
                </button>

                <div id="profile-menu" class="absolute right-0 mt-4 flex w-48 flex-col rounded-lg border border-gray-100 bg-surface shadow-xl hidden transition-all">
                    <ul class="flex flex-col gap-2 p-3">
                        <li>
                            <a href="#" class="flex items-center gap-3 rounded-md px-3 py-2 text-sm text-gray-700 hover:bg-primary/5 hover:text-primary transition">
                                <i class="fa-solid fa-gear"></i> Pengaturan
                            </a>
                        </li>
                        <li>
                            <hr class="border-gray-100">
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 rounded-md px-3 py-2 text-sm text-red-600 hover:bg-red-50 transition font-medium">
                                    <i class="fa-solid fa-right-from-bracket"></i> Keluar
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
    // Script sederhana untuk toggle dropdown profil
    document.getElementById('profile-btn').addEventListener('click', function(event) {
        event.stopPropagation(); // Mencegah klik menyebar
        document.getElementById('profile-menu').classList.toggle('hidden');
    });

    // Menutup dropdown profil jika user klik di luar area
    document.addEventListener('click', function(event) {
        const menu = document.getElementById('profile-menu');
        const btn = document.getElementById('profile-btn');
        if (!btn.contains(event.target) && !menu.contains(event.target)) {
            menu.classList.add('hidden');
        }
    });
</script>
<header class="bg-surface shadow-sm sticky top-0 z-30 px-6 py-4 flex justify-between items-center">
    <div>
        <h2 class="text-xl font-bold text-primary">@yield('title')</h2>
        <p class="text-xs text-gray-400">Sistem Informasi Pembayaran SPP</p>
    </div>
    <div class="flex items-center gap-4">
        <span class="hidden md:block text-sm font-medium">Randy (Developer)</span>
        <button onclick="confirmLogout()" class="bg-red-50 text-red-600 p-2 rounded-lg hover:bg-red-600 hover:text-white transition-all">
            <i class="fa-solid fa-right-from-bracket"></i>
        </button>
    </div>
</header>

<script>
    function confirmLogout() {
        Swal.fire({
            title: 'Logout?',
            text: "Anda akan keluar dari sesi ini.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#8B0000',
            confirmButtonText: 'Ya, Keluar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit form logout disini nanti
                window.location.href = '/login';
            }
        })
    }
</script>
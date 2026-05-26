<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SPP Digital')</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    },
                    colors: {
                        primary: '#8B0000', // Merah Tua
                        primary_hover: '#660000',
                        secondary: '#F59E0B', // Amber/Emas sbg pendukung
                        surface: '#FFFFFF', // Putih
                        background: '#F3F4F6' // Abu-abu sangat terang agar surface menonjol
                    }
                }
            }
        }
    </script>

    <style>
        /* Menghilangkan scrollbar tapi tetap bisa scroll (opsional utk UI yg lebih clean) */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        
        /* Animasi Loading Spinner */
        .loader {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #8B0000;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    </style>
</head>
<body class="bg-background text-gray-800 font-sans antialiased overflow-x-hidden">

    <div id="loading-screen" class="fixed inset-0 z-[9999] flex items-center justify-center bg-white bg-opacity-80 backdrop-blur-sm transition-opacity duration-300">
        <div class="flex flex-col items-center">
            <div class="loader mb-4"></div>
            <p class="text-primary font-semibold animate-pulse">Memuat Data...</p>
        </div>
    </div>

    <div class="flex h-screen overflow-hidden">
        
       @if(auth()->check() && auth()->user()->role != 'siswa')
           @if(auth()->user()->role == 'kepsek')
               @include('partials.sidebar_kepsek')
           @else
               @include('partials.sidebar')
           @endif
       @endif

        <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden">
            
            @if(auth()->check() && auth()->user()->role != 'siswa')
                @include('partials.header')
            @endif

            <main class="w-full flex-grow p-4 md:p-6 pb-24 md:pb-6">
                @yield('content')
            </main>

        </div>
    </div>

    @if(auth()->check() && auth()->user()->role === 'siswa')
        @include('partials.bottom-nav')
    @endif

    <script>
        // 1. Hilangkan Loading Screen saat halaman selesai dimuat
        window.addEventListener('load', function() {
            const loader = document.getElementById('loading-screen');
            loader.style.opacity = '0';
            setTimeout(() => { loader.style.display = 'none'; }, 300);
        });

        // 2. Global Error / Success Handling via SweetAlert
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#8B0000'
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ session('error') }}',
                confirmButtonColor: '#8B0000'
            });
        @endif

        // Tangkap error validasi dari Laravel
        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal!',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                confirmButtonColor: '#8B0000'
            });
        @endif
    </script>
    
    @stack('scripts')
</body>
</html>
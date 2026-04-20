<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | SPP Digital</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#8B0000', // Merah Tua
                        surface: '#FFFFFF',
                        secondary: '#475569', // Slate 600
                        light: '#F8FAFC'
                    },
                    fontFamily: {
                        poppins: ['Poppins', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #F8FAFC; }
        .loading-screen {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(255, 255, 255, 0.9);
            display: flex; justify-content: center; align-items: center;
            z-index: 9999;
        }
    </style>
</head>
<body class="bg-light text-secondary">

    <div id="loading-screen" class="loading-screen">
        <div class="flex flex-col items-center">
            <div class="animate-spin rounded-full h-12 w-12 border-t-4 border-primary"></div>
            <p class="mt-4 text-primary font-medium">Mohon Tunggu...</p>
        </div>
    </div>

    <div class="flex min-h-screen">
        @if(Request::is('admin*'))
            @include('layouts.partials.sidebar-admin')
        @elseif(Request::is('kepsek*'))
            @include('layouts.partials.sidebar-kepsek')
        @endif

        <div class="flex-1 flex flex-col min-w-0">
            @include('layouts.partials.header')

            <main class="flex-1 p-4 md:p-6 pb-24 md:pb-6">
                @yield('content')
            </main>
        </div>
    </div>

    @if(Request::is('siswa*'))
        @include('layouts.partials.bottom-nav-siswa')
    @endif

    <script>
        // Hide Loading Screen on Load
        window.addEventListener('load', () => {
            const loader = document.getElementById('loading-screen');
            setTimeout(() => { loader.style.display = 'none'; }, 500);
        });

        // Global SweetAlert for Flash Messages
        @if(session('success'))
            Swal.fire({ icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}", timer: 3000, showConfirmButton: false });
        @endif

        @if(session('error'))
            Swal.fire({ icon: 'error', title: 'Oops...', text: "{{ session('error') }}" });
        @endif
    </script>
</body>
</html>
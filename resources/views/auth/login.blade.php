<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SPP Digital</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bodymovin/5.12.2/lottie.min.js"></script>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Poppins', 'sans-serif'] },
                    colors: { primary: '#8B0000', surface: '#FFFFFF', background: '#F3F4F6' }
                }
            }
        }
    </script>
</head>
<body class="bg-background font-sans antialiased min-h-screen flex">

    <div class="hidden lg:flex lg:w-1/2 flex-col items-center justify-center p-12 relative overflow-hidden">
        
        <div class="text-center mb-8 z-10">
            <h1 class="text-4xl font-bold text-gray-800 mb-4">Sistem Manajemen SPP</h1>
            <p class="text-gray-600 font-medium">Kelola administrasi dan pembayaran sekolah<br>dengan lebih mudah, cepat, dan transparan.</p>
        </div>

        <div id="lottie-container" class="w-full max-w-lg z-10"></div>
        
        <div class="absolute top-0 left-0 w-64 h-64 bg-primary/5 rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 right-0 w-80 h-80 bg-primary/10 rounded-full blur-3xl translate-x-1/3 translate-y-1/3"></div>
    </div>

    <div class="w-full lg:w-1/2 flex items-center justify-center bg-surface shadow-[-10px_0_30px_rgba(0,0,0,0.05)] z-20 px-6 sm:px-12 py-12 relative">
        
        <div class="w-full max-w-md">
            <div class="text-center mb-10">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary/10 mb-5 shadow-inner">
                    <i class="fa-solid fa-wallet text-3xl text-primary"></i>
                </div>
                <h2 class="text-3xl font-bold text-gray-800">SPP Digital</h2>
                <p class="text-sm text-gray-500 mt-2">Silakan masuk ke akun Anda untuk melanjutkan</p>
            </div>

            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf
                
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-regular fa-envelope text-gray-400"></i>
                        </div>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                            class="block w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all sm:text-sm bg-gray-50 focus:bg-white"
                            placeholder="admin@sekolah.com">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-lock text-gray-400"></i>
                        </div>
                        <input type="password" name="password" id="password" required
                            class="block w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all sm:text-sm bg-gray-50 focus:bg-white"
                            placeholder="••••••••">
                    </div>
                </div>


                <button type="submit" 
                    class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-md text-sm font-bold text-white bg-primary hover:bg-[#660000] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all hover:shadow-lg active:scale-[0.98]">
                    Masuk Sekarang <i class="fa-solid fa-arrow-right ml-2 mt-0.5"></i>
                </button>
            </form>
        </div>
    </div>

    <script>
        // 1. Script untuk Menjalankan Animasi Lottie (JSON)
        document.addEventListener('DOMContentLoaded', function() {
            lottie.loadAnimation({
                container: document.getElementById('lottie-container'), // ID div penampung
                renderer: 'svg',
                loop: true,
                autoplay: true,
                // Pastikan file JSON ada di folder public/
                path: '{{ asset("The guy with the cat at the computer.json") }}' 
            });
        });

        // 2. Script Notifikasi SweetAlert
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal Login!',
                text: '{{ session('error') }}',
                confirmButtonColor: '#8B0000',
                customClass: { confirmButton: 'rounded-lg' }
            });
        @endif

        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#8B0000',
                customClass: { confirmButton: 'rounded-lg' }
            });
        @endif
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SPP Digital</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
<body class="bg-background font-sans antialiased flex items-center justify-center min-h-screen">

    <div class="w-full max-w-md p-8 m-4 bg-surface rounded-2xl shadow-lg border border-gray-100">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary/10 mb-4">
                <i class="fa-solid fa-wallet text-3xl text-primary"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-800">SPP Digital</h2>
            <p class="text-sm text-gray-500 mt-1">Silakan masuk ke akun Anda</p>
        </div>

        <form action="{{ route('login') }}" method="POST" class="space-y-5">
            @csrf
            
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-regular fa-envelope text-gray-400"></i>
                    </div>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                        class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary sm:text-sm"
                        placeholder="admin@sekolah.com">
                </div>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-lock text-gray-400"></i>
                    </div>
                    <input type="password" name="password" id="password" required
                        class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary sm:text-sm"
                        placeholder="••••••••">
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                    <label for="remember" class="ml-2 block text-sm text-gray-700">Ingat Saya</label>
                </div>
            </div>

            <button type="submit" 
                class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-primary hover:bg-[#660000] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                Masuk Sekarang
            </button>
        </form>
    </div>

    <script>
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal Login!',
                text: '{{ session('error') }}',
                confirmButtonColor: '#8B0000'
            });
        @endif

        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#8B0000'
            });
        @endif
    </script>
</body>
</html>
@extends('layouts.app')
@section('title', 'Profil Saya - SPP Digital')

@section('content')
<div class="max-w-md mx-auto relative -mx-4 -mt-4 sm:mx-auto sm:mt-0 bg-gray-50 min-h-screen pb-24">
    
    <div class="bg-primary pt-10 pb-20 px-5 text-white shadow-md relative overflow-hidden rounded-b-[2rem]">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full blur-xl transform translate-x-10 -translate-y-10"></div>
        
        <div class="flex items-center gap-4 relative z-10">
            <a href="{{ route('siswa.dashboard') }}" class="w-8 h-8 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center text-white hover:bg-white/30 transition active:scale-95">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h1 class="text-lg font-bold">Profil Saya</h1>
        </div>
    </div>

    <div class="px-5 -mt-12 relative z-20">
        <div class="bg-surface rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col items-center text-center">
            
            <div class="h-20 w-20 bg-gray-100 rounded-full flex items-center justify-center border-4 border-white shadow-md -mt-12 mb-3">
                <i class="fa-solid fa-user-graduate text-4xl text-gray-400"></i>
            </div>
            
            <h2 class="text-xl font-bold text-gray-800">{{ $user->name }}</h2>
            <p class="text-sm text-gray-500 mb-4">{{ $user->email }}</p>

            <div class="w-full grid grid-cols-2 gap-3 border-t border-gray-100 pt-4">
                <div class="bg-gray-50 rounded-xl p-3 text-left">
                    <p class="text-[10px] text-gray-500 font-bold uppercase">NISN</p>
                    <p class="text-sm font-bold text-gray-800">{{ $siswa->nisn ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-3 text-left">
                    <p class="text-[10px] text-gray-500 font-bold uppercase">Kelas</p>
                    <p class="text-sm font-bold text-gray-800">{{ $siswa->kelas->nama_kelas ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-3 text-left col-span-2">
                    <p class="text-[10px] text-gray-500 font-bold uppercase">Jurusan</p>
                    <p class="text-sm font-bold text-gray-800">{{ $siswa->kelas->jurusan->nama_jurusan ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="px-5 mt-6">
        <h3 class="text-sm font-bold text-gray-800 mb-3 px-1">Ganti Kata Sandi</h3>
        
        <form action="{{ route('siswa.profil.update_password') }}" method="POST" class="bg-surface rounded-2xl p-5 shadow-sm border border-gray-100 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Password Saat Ini</label>
                <div class="relative">
                    <input type="password" name="current_password" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-primary outline-none text-sm bg-gray-50" required>
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Password Baru</label>
                <input type="password" name="password" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-primary outline-none text-sm bg-gray-50" required>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-primary outline-none text-sm bg-gray-50" required>
            </div>
            
            <button type="submit" class="w-full bg-primary hover:bg-primary_hover text-white py-2.5 rounded-xl text-sm font-bold transition-colors shadow-sm mt-2">
                Simpan Password
            </button>
        </form>
    </div>

    <div class="px-5 mt-6">
        <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Apakah Anda yakin ingin keluar aplikasi?')">
            @csrf
            <button type="submit" class="w-full bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 py-3 rounded-2xl text-sm font-bold transition-colors shadow-sm flex items-center justify-center gap-2 active:scale-95">
                <i class="fa-solid fa-right-from-bracket"></i> Keluar Aplikasi
            </button>
        </form>
    </div>

</div>
@endsection
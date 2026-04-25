@extends('layouts.app')
@section('title', 'Home - SPP Digital')

@section('content')
    <div class="max-w-md mx-auto relative -mx-4 -mt-4 sm:mx-auto sm:mt-0 bg-gray-50 min-h-screen pb-20">

        <div
            class="bg-gradient-to-br from-primary to-[#b30000] rounded-b-[2.5rem] pt-10 pb-20 px-6 text-white shadow-md relative overflow-hidden">

            <div class="absolute -top-10 -right-10 w-32 h-32 bg-white opacity-10 rounded-full blur-2xl"></div>
            <div class="absolute top-10 -left-10 w-24 h-24 bg-white opacity-10 rounded-full blur-xl"></div>

            <div class="flex justify-between items-center relative z-10">
                <div>
                    <p class="text-xs text-primary-100 opacity-90 mb-0.5 tracking-wide">Selamat Datang,</p>
                    <h1 class="text-xl font-bold line-clamp-1">{{ auth()->user()->name }}</h1>
                    <div
                        class="mt-2 inline-block bg-black/20 backdrop-blur-sm px-3 py-1 rounded-full border border-white/20">
                        <p class="text-[10px] font-medium tracking-widest uppercase">
                            {{ $siswa->nisn ?? 'NISN -' }} • {{ $siswa->kelas->nama_kelas ?? 'KELAS -' }}
                        </p>
                    </div>
                </div>
                <div
                    class="h-14 w-14 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center border-2 border-white/40 shadow-inner shrink-0">
                    <i class="fa-solid fa-user-graduate text-2xl text-white"></i>
                </div>
            </div>
        </div>

        <div class="px-5 -mt-10 relative z-20">
            <div
                class="bg-surface rounded-2xl p-5 shadow-lg shadow-gray-200/50 border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1">Total Tagihan Aktif</p>
                    <h2 class="text-2xl font-black text-primary">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</h2>

                    @if ($tagihanTerakhir && $tagihanTerakhir->status == 'lunas')
                        <p class="text-[10px] text-green-600 mt-1 font-semibold"><i
                                class="fa-solid fa-check-circle mr-1"></i>Bulan ini sudah lunas</p>
                    @else
                        <p class="text-[10px] text-red-500 mt-1 font-semibold"><i
                                class="fa-solid fa-circle-exclamation mr-1"></i>Segera lakukan pembayaran</p>
                    @endif
                </div>

                <a href="#"
                    class="h-12 w-12 rounded-full bg-secondary text-white flex flex-col items-center justify-center text-xl shadow-md hover:scale-105 transition-transform">
                    <i class="fa-solid fa-wallet"></i>
                </a>
            </div>
        </div>

        <div class="px-5 mt-8">
            <h3 class="text-sm font-bold text-gray-800 mb-4 px-1">Menu Layanan</h3>

            <div class="grid grid-cols-3 gap-3 md:gap-4">

                <a href="{{ route('siswa.tagihan.index') }}"
                    class="bg-surface rounded-2xl p-4 flex flex-col items-center justify-center gap-3 shadow-sm border border-gray-100 hover:bg-primary/5 transition active:scale-95">
                    <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                        <i class="fa-solid fa-file-invoice-dollar"></i>
                    </div>
                    <span class="text-[11px] font-bold text-gray-700 text-center">Tagihan Saya</span>
                </a>

                <a href="{{ route('siswa.riwayat.index') }}"
                    class="bg-surface rounded-2xl p-4 flex flex-col items-center justify-center gap-3 shadow-sm border border-gray-100 hover:bg-primary/5 transition active:scale-95">
                    <div class="w-12 h-12 rounded-full bg-green-50 text-green-600 flex items-center justify-center text-xl">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </div>
                    <span class="text-[11px] font-bold text-gray-700 text-center">Riwayat</span>
                </a>

                <a href="{{ route('siswa.notifikasi.index') }}"
                    class="bg-surface rounded-2xl p-4 flex flex-col items-center justify-center gap-3 shadow-sm border border-gray-100 hover:bg-primary/5 transition active:scale-95 relative">

                    <div
                        class="w-12 h-12 rounded-full bg-orange-50 text-orange-600 flex items-center justify-center text-xl">
                        <i class="fa-solid fa-bell"></i>
                    </div>
                    <span class="text-[11px] font-bold text-gray-700 text-center">Notifikasi</span>
                </a>
                <a href="{{ route('siswa.profil.index') }}"
                    class="bg-surface rounded-2xl p-4 flex flex-col items-center justify-center gap-3 shadow-sm border border-gray-100 hover:bg-primary/5 transition active:scale-95">
                    <div class="w-12 h-12 rounded-full bg-teal-50 text-teal-600 flex items-center justify-center text-xl">
                        <i class="fa-solid fa-user-shield"></i>
                    </div>
                    <span class="text-[11px] font-bold text-gray-700 text-center">Profil Saya</span>
                </a>

                <a href="#"
                    class="bg-surface rounded-2xl p-4 flex flex-col items-center justify-center gap-3 shadow-sm border border-gray-100 hover:bg-primary/5 transition active:scale-95">
                    <div class="w-12 h-12 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center text-xl">
                        <i class="fa-solid fa-headset"></i>
                    </div>
                    <span class="text-[11px] font-bold text-gray-700 text-center">Bantuan</span>
                </a>

            </div>
        </div>

        <div class="px-5 mt-8 mb-6">
            <div class="flex justify-between items-center mb-4 px-1">
                <h3 class="text-sm font-bold text-gray-800">Pesan & Pengumuman</h3>
                <a href="#" class="text-[11px] text-primary font-bold hover:underline">Lihat Semua</a>
            </div>

            <div class="space-y-3">
                @forelse($notifikasi as $notif)
                    <div
                        class="bg-surface rounded-2xl p-4 shadow-sm border border-gray-100 flex gap-4 items-start active:bg-gray-50 transition">
                        <div
                            class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 
                        {{ $notif->jenis == 'reminder' ? 'bg-red-50 text-red-500' : ($notif->jenis == 'tagihan_baru' ? 'bg-blue-50 text-blue-500' : 'bg-green-50 text-green-500') }}">
                            <i
                                class="fa-solid {{ $notif->jenis == 'reminder' ? 'fa-bell' : ($notif->jenis == 'tagihan_baru' ? 'fa-file-invoice' : 'fa-envelope') }} text-lg"></i>
                        </div>
                        <div class="flex-grow">
                            <h4 class="text-xs font-bold text-gray-800">{{ $notif->judul }}</h4>
                            <p class="text-[11px] text-gray-500 mt-1 line-clamp-2 leading-relaxed">{{ $notif->pesan }}</p>
                            <span class="text-[9px] text-gray-400 mt-2 block font-medium"><i
                                    class="fa-regular fa-clock mr-1"></i>{{ $notif->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                @empty
                    <div
                        class="bg-surface rounded-2xl p-6 shadow-sm border border-gray-100 text-center flex flex-col items-center">
                        <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mb-2">
                            <i class="fa-regular fa-bell-slash text-gray-400 text-xl"></i>
                        </div>
                        <p class="text-xs font-medium text-gray-500">Belum ada notifikasi baru.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection

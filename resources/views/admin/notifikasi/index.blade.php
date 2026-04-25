@extends('layouts.app')
@section('title', 'Notifikasi & Pengaturan')

@section('content')
<div class="space-y-6">

    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-800">Notifikasi & Pengaturan</h2>
        <p class="text-sm text-gray-500 mt-1">Kelola pengingat otomatis dan kirim pesan manual ke siswa.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-2 space-y-6">
            
            <div class="bg-surface p-6 rounded-2xl shadow-sm border border-gray-100 border-t-4 border-t-secondary">
                <h3 class="text-lg font-bold text-gray-800 mb-4"><i class="fa-solid fa-robot text-secondary mr-2"></i> Pengaturan Notifikasi Otomatis</h3>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <div>
                            <p class="font-bold text-gray-800">Tagihan Baru</p>
                            <p class="text-xs text-gray-500 mt-1">Otomatis kirim notifikasi saat tagihan bulanan di-generate.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" value="" class="sr-only peer" checked>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <div>
                            <p class="font-bold text-gray-800">Reminder H-3 Jatuh Tempo</p>
                            <p class="text-xs text-gray-500 mt-1">Kirim pengingat otomatis 3 hari sebelum batas pembayaran berakhir.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" value="" class="sr-only peer" checked>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                        </label>
                    </div>
                </div>
                <p class="text-[10px] text-gray-400 mt-3 italic">*Fitur otomatis berjalan di latar belakang (Background Jobs).</p>
            </div>

            <div class="bg-surface p-6 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-800 mb-4"><i class="fa-solid fa-paper-plane text-primary mr-2"></i> Kirim Notifikasi Manual (Blast)</h3>
                
                <form action="{{ route('admin.notifikasi.blast') }}" method="POST" class="space-y-4" onsubmit="showLoading()">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Target Penerima</label>
                            <select name="target" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-primary outline-none text-sm bg-gray-50" required>
                                <option value="semua_siswa">Semua Siswa Terdaftar</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Jenis Pesan</label>
                            <select name="jenis" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-primary outline-none text-sm bg-white" required>
                                <option value="reminder">Reminder Tagihan</option>
                                <option value="tagihan_baru">Pemberitahuan Tagihan Baru</option>
                                <option value="info">Informasi Umum</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Judul Notifikasi</label>
                        <input type="text" name="judul" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-primary outline-none text-sm" placeholder="Contoh: Pengingat Pembayaran SPP Bulan Ini" required>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Isi Pesan</label>
                        <textarea name="pesan" rows="4" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-primary outline-none text-sm" placeholder="Tuliskan pesan yang ingin dikirimkan ke siswa..." required></textarea>
                    </div>

                    <div class="flex justify-end pt-2">
                        <button type="submit" class="bg-primary hover:bg-primary_hover text-white px-6 py-2 rounded-lg text-sm font-bold transition-colors shadow-sm flex items-center">
                            <i class="fa-solid fa-paper-plane mr-2"></i> Kirim Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="lg:col-span-1 bg-surface p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col h-[600px]">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex justify-between items-center">
                <span>Riwayat Terkirim</span>
                <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-md">{{ $riwayat->count() }} Data</span>
            </h3>
            
            <div class="flex-grow overflow-y-auto pr-2 space-y-4 no-scrollbar">
                @forelse($riwayat as $notif)
                    <div class="p-3 border border-gray-100 rounded-xl hover:bg-gray-50 transition">
                        <div class="flex items-start justify-between mb-1">
                            <span class="text-[10px] font-bold uppercase tracking-wider 
                                {{ $notif->jenis == 'reminder' ? 'text-red-500' : ($notif->jenis == 'tagihan_baru' ? 'text-blue-500' : 'text-green-500') }}">
                                {{ str_replace('_', ' ', $notif->jenis) }}
                            </span>
                            <span class="text-[10px] text-gray-400">{{ $notif->created_at->diffForHumans() }}</span>
                        </div>
                        <h4 class="font-bold text-gray-800 text-sm mb-1 leading-tight">{{ $notif->judul }}</h4>
                        <p class="text-xs text-gray-500 line-clamp-2">{{ $notif->pesan }}</p>
                        <p class="text-[10px] text-gray-400 mt-2 font-medium">Penerima: {{ $notif->user->name ?? 'Siswa' }}</p>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center h-full text-gray-400">
                        <i class="fa-regular fa-bell-slash text-3xl mb-2"></i>
                        <p class="text-sm">Belum ada notifikasi terkirim</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script>
    function showLoading() {
        document.getElementById('loading-screen').style.display = 'flex';
        document.getElementById('loading-screen').style.opacity = '1';
    }
</script>
@endsection
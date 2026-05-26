@extends('layouts.app')
@section('title', 'Pengaturan Sistem')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-xl font-bold text-gray-800">Pengaturan Aplikasi & Instansi</h2>
        <p class="text-sm text-gray-500 mt-1">Kustomisasi identitas aplikasi, logo sidebar, serta kebutuhan kop surat laporan.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-1 bg-surface p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col items-center justify-center text-center">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-6">Preview Identitas Sidebar</p>
            
            <div class="w-full bg-[#8B0000] p-6 rounded-2xl flex items-center gap-3 shadow-md border border-white/10 text-left">
                <div class="bg-white/10 p-2 rounded-xl text-white shrink-0">
                    @if($pengaturan->logo)
                        <img src="{{ asset('storage/' . $pengaturan->logo) }}" class="h-8 w-8 object-contain rounded">
                    @else
                        <i class="fa-solid fa-graduation-cap text-2xl"></i>
                    @endif
                </div>
                <div class="flex flex-col overflow-hidden">
                    <span class="text-lg font-bold tracking-tight text-white truncate">{{ $pengaturan->nama_sistem }}</span>
                    <span class="text-[10px] text-white/60 uppercase tracking-[1.5px] -mt-0.5 truncate">{{ $pengaturan->slogan_sistem }}</span>
                </div>
            </div>

            <p class="text-xs text-gray-400 mt-6 italic">Perubahan akan langsung diterapkan ke seluruh halaman layouts admin, siswa, dan kepala sekolah.</p>
        </div>

        <div class="lg:col-span-2 bg-surface p-6 rounded-2xl shadow-sm border border-gray-100">
            <form action="{{ route('admin.pengaturan.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Sistem / Sekolah</label>
                        <input type="text" name="nama_sistem" value="{{ old('nama_sistem', $pengaturan->nama_sistem) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-primary outline-none" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Slogan / Sub-title</label>
                        <input type="text" name="slogan_sistem" value="{{ old('slogan_sistem', $pengaturan->slogan_sistem) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-primary outline-none" required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Upload Logo Baru <span class="text-xs font-normal text-gray-400">(Format: PNG/JPG, Max: 2MB)</span></label>
                    <input type="file" name="logo" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 cursor-pointer">
                </div>

                <div class="border-t border-gray-100 pt-4">
                    <h4 class="text-sm font-bold text-gray-800 mb-3"><i class="fa-solid fa-file-invoice text-gray-400 mr-1"></i> Data Kop Laporan Instansi</h4>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-1">Nomor Telepon Sekolah</label>
                            <input type="text" name="telepon_sekolah" value="{{ old('telepon_sekolah', $pengaturan->telepon_sekolah) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-primary outline-none" placeholder="Contoh: (061) 123456">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-1">Email Resmi Sekolah</label>
                            <input type="email" name="email_sekolah" value="{{ old('email_sekolah', $pengaturan->email_sekolah) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-primary outline-none" placeholder="Contoh: smk@sekolah.sch.id">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-1">Alamat Lengkap Instansi</label>
                        <textarea name="alamat_sekolah" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-primary outline-none" placeholder="Tuliskan alamat lengkap sekolah untuk kop surat laporan..."></textarea>
                    </div>
                </div>

                <div class="flex justify-end pt-3">
                    <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white px-6 py-2.5 rounded-lg text-sm font-bold transition-colors shadow-sm">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
        
    </div>
</div>
@endsection
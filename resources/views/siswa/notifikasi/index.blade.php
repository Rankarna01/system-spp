@extends('layouts.app')
@section('title', 'Notifikasi - SPP Digital')

@section('content')
<div class="max-w-md mx-auto relative -mx-4 -mt-4 sm:mx-auto sm:mt-0 bg-gray-50 min-h-screen pb-24">
    
    <div class="bg-surface sticky top-0 z-30 px-5 py-4 flex items-center justify-between shadow-sm border-b border-gray-100">
        <div class="flex items-center gap-4">
            <a href="{{ route('siswa.dashboard') }}" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 hover:bg-gray-200 transition active:scale-95">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h1 class="text-lg font-bold text-gray-800">Notifikasi</h1>
        </div>
        <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center text-primary relative">
            <i class="fa-solid fa-bell"></i>
        </div>
    </div>

    <div class="px-5 mt-5 space-y-4">
        
        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Pesan & Informasi Terbaru</p>

        <div class="space-y-3">
            @forelse($notifikasi as $notif)
                <div class="bg-surface rounded-2xl p-4 shadow-sm border {{ $notif->is_read ? 'border-gray-100' : 'border-primary/30 bg-primary/5' }} flex gap-4 items-start active:bg-gray-50 transition relative overflow-hidden">
                    
                    @if(!$notif->is_read)
                        <span class="absolute top-3 right-3 w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                    @endif

                    <div class="w-12 h-12 rounded-full flex items-center justify-center shrink-0 
                        {{ $notif->jenis == 'reminder' ? 'bg-red-50 text-red-500' : ($notif->jenis == 'tagihan_baru' ? 'bg-blue-50 text-blue-500' : 'bg-green-50 text-green-500') }}">
                        <i class="fa-solid {{ $notif->jenis == 'reminder' ? 'fa-bell-ringing' : ($notif->jenis == 'tagihan_baru' ? 'fa-file-invoice-dollar' : 'fa-circle-info') }} text-xl"></i>
                    </div>

                    <div class="flex-grow pr-2">
                        <div class="flex justify-between items-start mb-0.5">
                            <span class="text-[9px] font-bold uppercase tracking-wider 
                                {{ $notif->jenis == 'reminder' ? 'text-red-500' : ($notif->jenis == 'tagihan_baru' ? 'text-blue-500' : 'text-green-500') }}">
                                {{ str_replace('_', ' ', $notif->jenis) }}
                            </span>
                        </div>
                        <h4 class="text-sm font-bold text-gray-800 leading-tight mb-1">{{ $notif->judul }}</h4>
                        <p class="text-xs text-gray-600 leading-relaxed">{{ $notif->pesan }}</p>
                        <span class="text-[10px] text-gray-400 mt-2 block font-medium">
                            <i class="fa-regular fa-clock mr-1"></i> {{ $notif->created_at->translatedFormat('d M Y, H:i') }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="bg-surface rounded-2xl p-8 shadow-sm border border-gray-100 text-center flex flex-col items-center mt-10">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4 relative">
                        <i class="fa-regular fa-bell-slash text-gray-300 text-3xl"></i>
                    </div>
                    <h3 class="font-bold text-gray-800 mb-1">Semua Tenang!</h3>
                    <p class="text-xs font-medium text-gray-500">Anda belum memiliki pesan atau notifikasi masuk saat ini.</p>
                </div>
            @endforelse
        </div>

    </div>
</div>
@endsection
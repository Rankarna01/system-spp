@extends('layouts.app')
@section('title', 'Riwayat Transaksi - SPP Digital')

@section('content')
<div class="max-w-md mx-auto relative -mx-4 -mt-4 sm:mx-auto sm:mt-0 bg-gray-50 min-h-screen pb-24">
    
    <div class="bg-surface sticky top-0 z-30 px-5 py-4 flex items-center justify-between shadow-sm border-b border-gray-100">
        <div class="flex items-center gap-4">
            <a href="{{ route('siswa.dashboard') }}" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 hover:bg-gray-200 transition active:scale-95">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h1 class="text-lg font-bold text-gray-800">Riwayat Pembayaran</h1>
        </div>
        <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center text-primary">
            <i class="fa-solid fa-clock-rotate-left"></i>
        </div>
    </div>

    <div class="px-5 mt-5 space-y-4">
        
        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Semua Transaksi Anda</p>

        @forelse($riwayat as $trx)
            <div class="bg-surface rounded-2xl p-4 shadow-sm border border-gray-100 flex gap-4 items-start active:bg-gray-50 transition">
                
                <div class="w-12 h-12 rounded-full flex items-center justify-center shrink-0 
                    {{ $trx->status == 'lunas' ? 'bg-green-50 text-green-500' : ($trx->status == 'menunggu' ? 'bg-yellow-50 text-yellow-600' : 'bg-red-50 text-red-500') }}">
                    <i class="fa-solid {{ $trx->status == 'lunas' ? 'fa-check' : ($trx->status == 'menunggu' ? 'fa-clock' : 'fa-xmark') }} text-xl"></i>
                </div>

                <div class="flex-grow">
                    <div class="flex justify-between items-start mb-1">
                        <h4 class="text-sm font-bold text-gray-800">SPP {{ $trx->tagihan->bulan ?? '-' }}</h4>
                        <span class="text-sm font-black text-gray-800">Rp {{ number_format($trx->gross_amount, 0, ',', '.') }}</span>
                    </div>
                    
                    <p class="text-xs text-gray-500 mb-1 font-mono uppercase">{{ $trx->bank }} VA: {{ $trx->va_number ?? '-' }}</p>
                    
                    <div class="flex justify-between items-center mt-2">
                        <span class="text-[10px] text-gray-400 font-medium">
                            <i class="fa-regular fa-calendar mr-1"></i>
                            {{ \Carbon\Carbon::parse($trx->waktu_transaksi)->translatedFormat('d M Y, H:i') }}
                        </span>
                        
                        @if($trx->status == 'lunas')
                            <span class="text-[10px] font-bold text-green-600 uppercase tracking-wide">Berhasil</span>
                        @elseif($trx->status == 'menunggu')
                            <span class="text-[10px] font-bold text-yellow-600 uppercase tracking-wide">Menunggu</span>
                        @else
                            <span class="text-[10px] font-bold text-red-500 uppercase tracking-wide">Gagal/Expired</span>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-surface rounded-2xl p-8 shadow-sm border border-gray-100 text-center flex flex-col items-center mt-10">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                    <i class="fa-solid fa-receipt text-gray-300 text-3xl"></i>
                </div>
                <h3 class="font-bold text-gray-800 mb-1">Belum ada riwayat</h3>
                <p class="text-xs font-medium text-gray-500">Anda belum pernah melakukan pembuatan tagihan / transaksi pembayaran.</p>
            </div>
        @endforelse

    </div>
</div>
@endsection
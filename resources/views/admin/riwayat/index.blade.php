@extends('layouts.app')
@section('title', 'Riwayat Transaksi')

@section('content')
<div class="bg-surface p-4 md:p-6 rounded-2xl shadow-sm border border-gray-100">
    
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-800">Riwayat Transaksi</h2>
        <p class="text-sm text-gray-500 mt-1">Pantau semua histori pembuatan VA dan pembayaran SPP.</p>
    </div>

    <form action="{{ route('admin.riwayat.index') }}" method="GET" class="bg-gray-50 p-4 rounded-xl border border-gray-200 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Cari Siswa / Order ID</label>
                <input type="text" name="search" value="{{ request('search') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-primary outline-none text-sm" placeholder="Nama atau SPP-123...">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Status Pembayaran</label>
                <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-primary outline-none text-sm bg-white">
                    <option value="">Semua Status</option>
                    <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                    <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu (VA Aktif)</option>
                    <option value="gagal" {{ request('status') == 'gagal' ? 'selected' : '' }}>Gagal / Expired</option>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Dari Tanggal</label>
                    <input type="date" name="tanggal_awal" value="{{ request('tanggal_awal') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-primary outline-none text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Sampai</label>
                    <input type="date" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-primary outline-none text-sm">
                </div>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="w-full bg-primary hover:bg-primary_hover text-white px-4 py-2 rounded-lg text-sm font-bold transition-colors">
                    <i class="fa-solid fa-filter mr-1"></i> Filter
                </button>
                <a href="{{ route('admin.riwayat.index') }}" class="w-full bg-white border border-gray-300 hover:bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm font-bold transition-colors text-center">
                    Reset
                </a>
            </div>
        </div>
    </form>

    <div class="overflow-x-auto pb-6">
        <table class="w-full text-left border-collapse min-w-max">
            <thead class="bg-gray-50 text-gray-500 text-sm border-y border-gray-100">
                <tr>
                    <th class="py-3 px-4 font-semibold">Waktu Transaksi</th>
                    <th class="py-3 px-4 font-semibold">Order ID</th>
                    <th class="py-3 px-4 font-semibold">Siswa & Kelas</th>
                    <th class="py-3 px-4 font-semibold">Nominal</th>
                    <th class="py-3 px-4 font-semibold">Status</th>
                    <th class="py-3 px-4 font-semibold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-700">
                @forelse($transaksi as $trx)
                <tr class="border-b border-gray-50 hover:bg-gray-50 transition-colors">
                    <td class="py-3 px-4 text-gray-600">{{ \Carbon\Carbon::parse($trx->waktu_transaksi)->translatedFormat('d M Y, H:i') }}</td>
                    <td class="py-3 px-4 font-mono font-bold text-gray-800">{{ $trx->order_id }}</td>
                    <td class="py-3 px-4">
                        <p class="font-bold text-gray-900">{{ $trx->tagihan->siswa->nama ?? '-' }}</p>
                        <p class="text-xs text-gray-500">{{ $trx->tagihan->siswa->kelas->nama_kelas ?? '-' }}</p>
                    </td>
                    <td class="py-3 px-4 font-bold text-primary">Rp {{ number_format($trx->gross_amount, 0, ',', '.') }}</td>
                    <td class="py-3 px-4">
                        @if($trx->status == 'lunas')
                            <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-bold"><i class="fa-solid fa-check mr-1"></i>Lunas</span>
                        @elseif($trx->status == 'menunggu')
                            <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-xs font-bold"><i class="fa-solid fa-clock mr-1"></i>Menunggu</span>
                        @else
                            <span class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-bold"><i class="fa-solid fa-xmark mr-1"></i>Gagal/Expired</span>
                        @endif
                    </td>
                    <td class="py-3 px-4 text-center">
                        <button onclick="openModalDetail(
                            '{{ $trx->order_id }}', 
                            '{{ \Carbon\Carbon::parse($trx->waktu_transaksi)->translatedFormat('d F Y, H:i:s') }}',
                            '{{ $trx->tagihan->siswa->nama ?? '-' }}',
                            '{{ $trx->tagihan->siswa->kelas->nama_kelas ?? '-' }}',
                            '{{ $trx->tagihan->bulan }} {{ $trx->tagihan->tahun }}',
                            'Rp {{ number_format($trx->gross_amount, 0, ',', '.') }}',
                            '{{ strtoupper($trx->bank) }}',
                            '{{ $trx->va_number ?? '-' }}',
                            '{{ $trx->status }}'
                        )" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1.5 rounded-md text-xs font-bold transition-colors">
                            <i class="fa-solid fa-eye mr-1"></i> Detail
                        </button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-6 text-gray-500">Tidak ada data transaksi yang ditemukan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $transaksi->links('pagination::tailwind') }}
    </div>
</div>

<div id="modal-detail" class="fixed inset-0 z-[100] hidden bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity">
    <div class="bg-surface w-full max-w-md rounded-2xl shadow-xl overflow-hidden transform transition-all">
        
        <div class="bg-primary p-5 flex justify-between items-center text-white">
            <div>
                <h3 class="text-lg font-bold">Detail Transaksi</h3>
                <p id="det_order_id" class="text-xs font-mono text-primary-100 mt-1">SPP-...</p>
            </div>
            <button onclick="closeModalDetail()" class="text-white hover:text-gray-200"><i class="fa-solid fa-xmark text-xl"></i></button>
        </div>

        <div class="p-5 space-y-4 text-sm text-gray-700">
            <div class="flex justify-between border-b border-gray-100 pb-2">
                <span class="font-medium text-gray-500">Status Pembayaran</span>
                <span id="det_status" class="font-bold uppercase text-right">STATUS</span>
            </div>
            <div class="flex justify-between border-b border-gray-100 pb-2">
                <span class="font-medium text-gray-500">Waktu Transaksi</span>
                <span id="det_waktu" class="font-bold text-right">-</span>
            </div>
            <div class="flex justify-between border-b border-gray-100 pb-2">
                <span class="font-medium text-gray-500">Tagihan Bulan</span>
                <span id="det_bulan" class="font-bold text-right">-</span>
            </div>
            <div class="flex justify-between border-b border-gray-100 pb-2">
                <span class="font-medium text-gray-500">Nama Siswa</span>
                <div class="text-right">
                    <p id="det_nama" class="font-bold">-</p>
                    <p id="det_kelas" class="text-xs text-gray-500">-</p>
                </div>
            </div>
            <div class="flex justify-between border-b border-gray-100 pb-2">
                <span class="font-medium text-gray-500">Metode Pembayaran</span>
                <div class="text-right">
                    <p id="det_bank" class="font-bold">-</p>
                    <p id="det_va" class="font-mono text-xs text-gray-500">VA: -</p>
                </div>
            </div>
            <div class="flex justify-between pt-2">
                <span class="font-bold text-gray-800 text-base">Total Nominal</span>
                <span id="det_nominal" class="font-bold text-primary text-base">-</span>
            </div>
        </div>

        <div class="p-5 border-t border-gray-100 bg-gray-50 flex justify-end">
            <button onclick="closeModalDetail()" class="px-5 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Tutup</button>
        </div>
    </div>
</div>

<script>
    function openModalDetail(orderId, waktu, nama, kelas, bulan, nominal, bank, va, status) {
        document.getElementById('modal-detail').classList.remove('hidden');
        
        // Populate Data
        document.getElementById('det_order_id').innerText = orderId;
        document.getElementById('det_waktu').innerText = waktu;
        document.getElementById('det_nama').innerText = nama;
        document.getElementById('det_kelas').innerText = kelas;
        document.getElementById('det_bulan').innerText = bulan;
        document.getElementById('det_nominal').innerText = nominal;
        document.getElementById('det_bank').innerText = bank + " Virtual Account";
        document.getElementById('det_va').innerText = "VA: " + va;

        // Styling Status Text
        const statusEl = document.getElementById('det_status');
        statusEl.innerText = status;
        if(status === 'lunas') {
            statusEl.className = "font-bold uppercase text-right text-green-600";
        } else if(status === 'menunggu') {
            statusEl.className = "font-bold uppercase text-right text-yellow-600";
        } else {
            statusEl.className = "font-bold uppercase text-right text-red-600";
        }
    }

    function closeModalDetail() { 
        document.getElementById('modal-detail').classList.add('hidden'); 
    }
</script>
@endsection
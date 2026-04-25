@extends('layouts.app')

@section('title', 'Laporan & Keuangan - Executive')

@section('content')
<div class="space-y-6">

    <div class="bg-surface p-2 rounded-2xl shadow-sm border border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4">
        <div class="flex p-1 bg-gray-50 rounded-xl w-full sm:w-auto">
            <a href="{{ route('kepsek.laporan.index', ['jenis' => 'keuangan']) }}" class="flex-1 sm:flex-none px-6 py-2.5 text-sm font-bold text-center rounded-lg transition-all {{ $jenis == 'keuangan' ? 'bg-white text-primary shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                <i class="fa-solid fa-money-bill-trend-up mr-2"></i> Laporan Keuangan
            </a>
            <a href="{{ route('kepsek.laporan.index', ['jenis' => 'tunggakan']) }}" class="flex-1 sm:flex-none px-6 py-2.5 text-sm font-bold text-center rounded-lg transition-all {{ $jenis == 'tunggakan' ? 'bg-white text-red-600 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                <i class="fa-solid fa-triangle-exclamation mr-2"></i> Laporan Tunggakan
            </a>
        </div>
        <div class="px-4">
            <button onclick="window.print()" class="text-gray-600 hover:text-primary font-bold text-sm bg-gray-100 px-4 py-2 rounded-lg transition-colors">
                <i class="fa-solid fa-print mr-1"></i> Cetak Laporan
            </button>
        </div>
    </div>

    <form action="{{ route('kepsek.laporan.index') }}" method="GET" class="bg-surface p-5 rounded-2xl shadow-sm border border-gray-100 no-print">
        <input type="hidden" name="jenis" value="{{ $jenis }}">
        
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-bold text-gray-800"><i class="fa-solid fa-filter text-gray-400 mr-2"></i> Filter Data Laporan</h3>
            <a href="{{ route('kepsek.laporan.index', ['jenis' => $jenis]) }}" class="text-xs text-primary font-bold hover:underline">Reset Filter</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-2 grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Periode Awal</label>
                    <input type="date" name="tanggal_awal" value="{{ request('tanggal_awal') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-primary outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Periode Akhir</label>
                    <input type="date" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-primary outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Kelas</label>
                <select name="kelas_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-primary outline-none bg-white">
                    <option value="">Semua Kelas</option>
                    @foreach($kelas as $k)
                        <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Jurusan</label>
                <select name="jurusan_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-primary outline-none bg-white">
                    <option value="">Semua Jurusan</option>
                    @foreach($jurusan as $j)
                        <option value="{{ $j->id }}" {{ request('jurusan_id') == $j->id ? 'selected' : '' }}>{{ $j->nama_jurusan }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mt-4 flex justify-end">
            <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white px-6 py-2 rounded-lg text-sm font-bold transition-colors">Terapkan Filter</button>
        </div>
    </form>

    <div class="bg-surface rounded-2xl shadow-sm border border-gray-100 overflow-hidden print-area">
        <div class="p-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
            <div>
                <h3 class="text-lg font-bold text-gray-800">{{ $jenis == 'keuangan' ? 'Riwayat Pemasukan (Lunas)' : 'Daftar Siswa Menunggak' }}</h3>
                <p class="text-xs text-gray-500 mt-1">Total Data: {{ $dataLaporan->total() }} record</p>
            </div>
            <div class="text-right">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">TOTAL NOMINAL</p>
                <h2 class="text-xl font-black {{ $jenis == 'keuangan' ? 'text-primary' : 'text-red-600' }}">Rp {{ number_format($totalNominal, 0, ',', '.') }}</h2>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-max">
                <thead class="bg-white text-gray-500 text-sm border-b border-gray-100">
                    <tr>
                        <th class="py-3 px-4 font-semibold w-16">No</th>
                        <th class="py-3 px-4 font-semibold">Nama Siswa</th>
                        <th class="py-3 px-4 font-semibold">Kelas / Jurusan</th>
                        @if($jenis == 'keuangan')
                            <th class="py-3 px-4 font-semibold">Waktu Transaksi</th>
                            <th class="py-3 px-4 font-semibold">Metode</th>
                        @else
                            <th class="py-3 px-4 font-semibold">Bulan Tagihan</th>
                            <th class="py-3 px-4 font-semibold">Jatuh Tempo</th>
                        @endif
                        <th class="py-3 px-4 font-semibold text-right">Nominal</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700">
                    @forelse($dataLaporan as $key => $item)
                        @php 
                            // Penyesuaian variabel karena beda tabel
                            $siswa = $jenis == 'keuangan' ? $item->tagihan->siswa : $item->siswa;
                        @endphp
                    <tr class="border-b border-gray-50 hover:bg-gray-50 transition-colors">
                        <td class="py-3 px-4">{{ $dataLaporan->firstItem() + $key }}</td>
                        <td class="py-3 px-4 font-bold text-gray-900">{{ $siswa->nama ?? '-' }}</td>
                        <td class="py-3 px-4">
                            <span class="block text-xs font-bold text-gray-700">{{ $siswa->kelas->nama_kelas ?? '-' }}</span>
                            <span class="block text-[10px] text-gray-500">{{ $siswa->kelas->jurusan->nama_jurusan ?? '-' }}</span>
                        </td>
                        
                        @if($jenis == 'keuangan')
                            <td class="py-3 px-4 text-xs text-gray-600">{{ \Carbon\Carbon::parse($item->waktu_transaksi)->translatedFormat('d M Y, H:i') }}</td>
                            <td class="py-3 px-4 text-xs font-bold uppercase text-blue-600">{{ $item->bank ?? 'Manual' }}</td>
                            <td class="py-3 px-4 text-right font-bold text-primary">Rp {{ number_format($item->gross_amount, 0, ',', '.') }}</td>
                        @else
                            <td class="py-3 px-4 text-xs font-bold text-gray-800">{{ $item->bulan }} {{ $item->tahun }}</td>
                            <td class="py-3 px-4 text-xs text-red-500 font-medium">{{ \Carbon\Carbon::parse($item->jatuh_tempo)->translatedFormat('d M Y') }}</td>
                            <td class="py-3 px-4 text-right font-bold text-red-600">Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-10 text-gray-500">
                            <i class="fa-solid fa-folder-open text-4xl text-gray-300 mb-3 block"></i>
                            Tidak ada data laporan yang ditemukan untuk filter ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-4 border-t border-gray-100 bg-white no-print">
            {{ $dataLaporan->links('pagination::tailwind') }}
        </div>
    </div>
</div>

<style>
    @media print {
        body { background-color: white !important; }
        .no-print, #sidebar, header, nav { display: none !important; }
        .print-area { border: none !important; box-shadow: none !important; }
        /* Reset margin saat print */
        @page { margin: 1cm; }
    }
</style>
@endsection
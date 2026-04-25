@extends('layouts.app')
@section('title', 'Laporan & Statistik')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Laporan Keuangan & Statistik</h2>
            <p class="text-sm text-gray-500 mt-1">Ringkasan pemasukan, tunggakan, dan rekapitulasi data SPP.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-surface rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-4 border-l-4 border-primary">
            <div class="h-12 w-12 rounded-full bg-primary/10 text-primary flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-wallet"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Pemasukan</p>
                <h3 class="text-xl font-bold text-gray-800">Rp {{ number_format($kpi['total_pemasukan'], 0, ',', '.') }}</h3>
            </div>
        </div>

        <div class="bg-surface rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-4 border-l-4 border-red-500">
            <div class="h-12 w-12 rounded-full bg-red-50 text-red-500 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Tunggakan</p>
                <h3 class="text-xl font-bold text-gray-800">Rp {{ number_format($kpi['total_tunggakan'], 0, ',', '.') }}</h3>
            </div>
        </div>

        <div class="bg-surface rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-4 border-l-4 border-green-500">
            <div class="h-12 w-12 rounded-full bg-green-50 text-green-500 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-users"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Siswa Lunas</p>
                <h3 class="text-xl font-bold text-gray-800">{{ number_format($kpi['siswa_lunas']) }} <span class="text-sm font-normal text-gray-500">Siswa</span></h3>
            </div>
        </div>

        <div class="bg-surface rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-4 border-l-4 border-yellow-500">
            <div class="h-12 w-12 rounded-full bg-yellow-50 text-yellow-600 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-users-slash"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Menunggak</p>
                <h3 class="text-xl font-bold text-gray-800">{{ number_format($kpi['siswa_nunggak']) }} <span class="text-sm font-normal text-gray-500">Siswa</span></h3>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-2 bg-surface p-6 rounded-2xl shadow-sm border border-gray-100">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Grafik Pemasukan Tahun {{ $tahunIni }}</h3>
            <div class="relative h-72 w-full">
                <canvas id="laporanChart"></canvas>
            </div>
        </div>

        <div class="lg:col-span-1 bg-surface p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Cetak / Export Data</h3>
            <p class="text-sm text-gray-500 mb-6">Pilih jenis laporan dan rentang waktu untuk mengunduh data.</p>

            <form id="form-export" action="{{ route('admin.laporan.export.pdf') }}" method="POST" class="flex-grow flex flex-col space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Jenis Laporan</label>
                    <select name="jenis_laporan" id="jenis_laporan" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-primary outline-none text-sm bg-white" required>
                        <option value="pemasukan_bulanan">Rekap Pembayaran (Bulanan)</option>
                        <option value="tunggakan">Daftar Tunggakan Siswa</option>
                        <option value="rekap_kelas">Rekapitulasi per Kelas/Jurusan</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Bulan Mulai</label>
                        <input type="month" name="bulan_mulai" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-primary outline-none text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Sampai</label>
                        <input type="month" name="bulan_selesai" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-primary outline-none text-sm">
                    </div>
                </div>

                <div id="filter-kelas" class="hidden">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Pilih Kelas</label>
                    <select name="kelas_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-primary outline-none text-sm bg-white">
                        <option value="semua">Semua Kelas</option>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mt-auto pt-6 flex gap-3">
                    <button type="submit" onclick="document.getElementById('form-export').action='{{ route('admin.laporan.export.pdf') }}'" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-bold transition-colors shadow-sm flex items-center justify-center gap-2">
                        <i class="fa-solid fa-file-pdf"></i> PDF
                    </button>
                    <button type="submit" onclick="document.getElementById('form-export').action='{{ route('admin.laporan.export.excel') }}'" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-bold transition-colors shadow-sm flex items-center justify-center gap-2">
                        <i class="fa-solid fa-file-excel"></i> Excel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // 1. Logic memunculkan filter kelas
    document.getElementById('jenis_laporan').addEventListener('change', function() {
        const filterKelas = document.getElementById('filter-kelas');
        if(this.value === 'rekap_kelas') {
            filterKelas.classList.remove('hidden');
        } else {
            filterKelas.classList.add('hidden');
        }
    });

    // 2. Render Chart Pemasukan
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('laporanChart').getContext('2d');
        const dataPemasukan = @json($chartPemasukan); // Mengambil array data dari Controller
        
        let gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(139, 0, 0, 0.6)');   // Primary color with opacity
        gradient.addColorStop(1, 'rgba(139, 0, 0, 0.05)');

        new Chart(ctx, {
            type: 'bar', // Kita pakai Bar Chart agar beda dengan dashboard
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Total Pemasukan (Rp)',
                    data: dataPemasukan,
                    backgroundColor: gradient,
                    borderColor: '#8B0000',
                    borderWidth: 1,
                    borderRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) { label += ': '; }
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [4, 4], color: '#E5E7EB' },
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + (value/1000000) + 'M'; // Mempersingkat angka panjang
                            }
                        }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    });
</script>
@endpush
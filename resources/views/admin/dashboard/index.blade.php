@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col md:flex-row md:items-center md:justify-between bg-surface p-6 rounded-2xl shadow-sm border border-gray-100">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Selamat Datang, {{ auth()->user()->name ?? 'Admin' }}! 👋</h2>
            <p class="text-sm text-gray-500 mt-1">Ini adalah ringkasan sistem pembayaran SPP digital hari ini.</p>
        </div>
        <div class="mt-4 md:mt-0">
            <span class="inline-flex items-center gap-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-primary/10 text-primary">
                <i class="fa-regular fa-calendar"></i>
                {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-surface rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition-shadow">
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Total Siswa</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ number_format($totalSiswa, 0, ',', '.') }}</h3>
                <p class="text-xs text-green-500 mt-2 font-medium"><i class="fa-solid fa-arrow-trend-up"></i> +{{ $siswaBaruBulanIni }} bulan ini</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>

        <div class="bg-surface rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition-shadow">
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Pemasukan Bulan Ini</p>
                <h3 class="text-2xl font-bold text-gray-800">Rp {{ number_format($pemasukanBulanIni, 0, ',', '.') }}</h3>
                <p class="text-xs {{ $persentasePemasukan >= 0 ? 'text-green-500' : 'text-red-500' }} mt-2 font-medium">
                    <i class="fa-solid {{ $persentasePemasukan >= 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }}"></i> 
                    {{ $persentasePemasukan > 0 ? '+' : '' }}{{ number_format($persentasePemasukan, 1) }}% dari bulan lalu
                </p>
            </div>
            <div class="w-12 h-12 rounded-full bg-primary/10 text-primary flex items-center justify-center text-xl">
                <i class="fa-solid fa-wallet"></i>
            </div>
        </div>

        <div class="bg-surface rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition-shadow">
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Sudah Bayar</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ number_format($siswaSudahBayar, 0, ',', '.') }} <span class="text-sm font-normal text-gray-400">Siswa</span></h3>
                <p class="text-xs text-green-500 mt-2 font-medium">{{ number_format($persentaseSudahBayar, 1) }}% dari total siswa</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-green-50 text-green-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </div>

        <div class="bg-surface rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition-shadow">
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Tunggakan</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ number_format($siswaMenunggak, 0, ',', '.') }} <span class="text-sm font-normal text-gray-400">Siswa</span></h3>
                <p class="text-xs text-red-500 mt-2 font-medium">Rp {{ number_format($totalNominalTunggakan, 0, ',', '.') }} Total Tunggakan</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-red-50 text-red-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-surface rounded-2xl p-6 shadow-sm border border-gray-100 lg:col-span-2">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Tren Pembayaran SPP (6 Bulan Terakhir)</h3>
            <div class="relative h-72 w-full">
                <canvas id="pemasukanChart"></canvas>
            </div>
        </div>

        <div class="bg-surface rounded-2xl p-6 shadow-sm border border-gray-100 lg:col-span-1">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Status SPP Keseluruhan</h3>
            <div class="relative h-60 w-full flex items-center justify-center">
                <canvas id="statusChart"></canvas>
            </div>
            <div class="mt-4 flex justify-center gap-4 text-sm">
                <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-primary"></span> Lunas</div>
                <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full" style="background-color: #F59E0B"></span> Menunggu</div>
                <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-gray-200"></span> Belum</div>
            </div>
        </div>
    </div>

    <div class="bg-surface rounded-2xl p-6 shadow-sm border border-gray-100">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-800">Transaksi Terbaru</h3>
            <a href="{{ route('admin.riwayat.index') }}" class="text-sm text-primary hover:text-primary_hover font-medium">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-100 text-sm text-gray-500">
                        <th class="py-3 px-4 font-medium">ID Transaksi</th>
                        <th class="py-3 px-4 font-medium">Nama Siswa</th>
                        <th class="py-3 px-4 font-medium">Kelas</th>
                        <th class="py-3 px-4 font-medium">Nominal</th>
                        <th class="py-3 px-4 font-medium">Status</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700">
                    @forelse($transaksiTerbaru as $trx)
                        <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                            <td class="py-3 px-4 font-medium">{{ $trx->order_id }}</td>
                            <td class="py-3 px-4">{{ $trx->tagihan->siswa->nama ?? '-' }}</td>
                            <td class="py-3 px-4">{{ $trx->tagihan->siswa->kelas->nama_kelas ?? '-' }}</td>
                            <td class="py-3 px-4">Rp {{ number_format($trx->gross_amount, 0, ',', '.') }}</td>
                            <td class="py-3 px-4">
                                @if($trx->status == 'lunas')
                                    <span class="bg-green-100 text-green-700 px-2 py-1 rounded-md text-xs font-semibold">Berhasil</span>
                                @elseif($trx->status == 'menunggu')
                                    <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded-md text-xs font-semibold">Menunggu</span>
                                @else
                                    <span class="bg-red-100 text-red-700 px-2 py-1 rounded-md text-xs font-semibold">Gagal</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-4 text-center text-gray-500">Belum ada transaksi terbaru.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        Chart.defaults.font.family = "'Poppins', sans-serif";
        Chart.defaults.color = '#6B7280';

        // 1. Line Chart (Tren Pembayaran)
        const ctxPemasukan = document.getElementById('pemasukanChart').getContext('2d');
        
        let gradient = ctxPemasukan.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(139, 0, 0, 0.5)');   
        gradient.addColorStop(1, 'rgba(139, 0, 0, 0.0)');

        new Chart(ctxPemasukan, {
            type: 'line',
            data: {
                // Diambil dari Controller
                labels: {!! json_encode($chartLabels) !!},
                datasets: [{
                    label: 'Total Pemasukan (Juta Rp)',
                    data: {!! json_encode($chartPemasukanData) !!},
                    borderColor: '#8B0000', 
                    backgroundColor: gradient,
                    borderWidth: 2,
                    pointBackgroundColor: '#FFFFFF',
                    pointBorderColor: '#8B0000',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    fill: true,
                    tension: 0.4 
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [4, 4], color: '#E5E7EB' },
                        border: { display: false }
                    },
                    x: {
                        grid: { display: false },
                        border: { display: false }
                    }
                }
            }
        });

        // 2. Doughnut Chart (Status Pembayaran)
        const ctxStatus = document.getElementById('statusChart').getContext('2d');
        new Chart(ctxStatus, {
            type: 'doughnut',
            data: {
                labels: ['Lunas', 'Menunggu', 'Belum Bayar'],
                datasets: [{
                    // Diambil dari Controller
                    data: {!! json_encode($chartStatusData) !!},
                    backgroundColor: [
                        '#8B0000', // Primary
                        '#F59E0B', // Secondary
                        '#E5E7EB'  // Gray (Belum)
                    ],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%', 
                plugins: {
                    legend: { display: false }, 
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return ' ' + context.label + ': ' + context.raw + '%';
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
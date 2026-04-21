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
                <h3 class="text-2xl font-bold text-gray-800">1,250</h3>
                <p class="text-xs text-green-500 mt-2 font-medium"><i class="fa-solid fa-arrow-trend-up"></i> +12 bulan ini</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>

        <div class="bg-surface rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition-shadow">
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Pemasukan Bulan Ini</p>
                <h3 class="text-2xl font-bold text-gray-800">Rp 45.5M</h3>
                <p class="text-xs text-green-500 mt-2 font-medium"><i class="fa-solid fa-arrow-trend-up"></i> +5.2% dari bulan lalu</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-primary/10 text-primary flex items-center justify-center text-xl">
                <i class="fa-solid fa-wallet"></i>
            </div>
        </div>

        <div class="bg-surface rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition-shadow">
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Sudah Bayar</p>
                <h3 class="text-2xl font-bold text-gray-800">980 <span class="text-sm font-normal text-gray-400">Siswa</span></h3>
                <p class="text-xs text-green-500 mt-2 font-medium">78% dari total siswa</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-green-50 text-green-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </div>

        <div class="bg-surface rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition-shadow">
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Tunggakan</p>
                <h3 class="text-2xl font-bold text-gray-800">270 <span class="text-sm font-normal text-gray-400">Siswa</span></h3>
                <p class="text-xs text-red-500 mt-2 font-medium">Rp 13.5M Total Tunggakan</p>
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
            <h3 class="text-lg font-bold text-gray-800 mb-4">Status SPP Bulan Ini</h3>
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
            <a href="#" class="text-sm text-primary hover:text-primary_hover font-medium">Lihat Semua</a>
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
                    <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                        <td class="py-3 px-4 font-medium">TRX-00123</td>
                        <td class="py-3 px-4">Ahmad Fauzi</td>
                        <td class="py-3 px-4">XII RPL 1</td>
                        <td class="py-3 px-4">Rp 500.000</td>
                        <td class="py-3 px-4"><span class="bg-green-100 text-green-700 px-2 py-1 rounded-md text-xs font-semibold">Berhasil</span></td>
                    </tr>
                    <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                        <td class="py-3 px-4 font-medium">TRX-00122</td>
                        <td class="py-3 px-4">Budi Santoso</td>
                        <td class="py-3 px-4">XI TKJ 2</td>
                        <td class="py-3 px-4">Rp 500.000</td>
                        <td class="py-3 px-4"><span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded-md text-xs font-semibold">Menunggu</span></td>
                    </tr>
                    <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                        <td class="py-3 px-4 font-medium">TRX-00121</td>
                        <td class="py-3 px-4">Citra Lestari</td>
                        <td class="py-3 px-4">X Multimedia</td>
                        <td class="py-3 px-4">Rp 500.000</td>
                        <td class="py-3 px-4"><span class="bg-green-100 text-green-700 px-2 py-1 rounded-md text-xs font-semibold">Berhasil</span></td>
                    </tr>
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
        // Konfigurasi Global Chart.js agar menggunakan font Poppins
        Chart.defaults.font.family = "'Poppins', sans-serif";
        Chart.defaults.color = '#6B7280';

        // 1. Line Chart (Tren Pembayaran)
        const ctxPemasukan = document.getElementById('pemasukanChart').getContext('2d');
        
        // Membuat efek gradient untuk line chart
        let gradient = ctxPemasukan.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(139, 0, 0, 0.5)');   // Primary Merah Tua
        gradient.addColorStop(1, 'rgba(139, 0, 0, 0.0)');

        new Chart(ctxPemasukan, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                datasets: [{
                    label: 'Total Pemasukan (Juta Rp)',
                    data: [120, 190, 150, 220, 180, 250],
                    borderColor: '#8B0000', // Warna Primary
                    backgroundColor: gradient,
                    borderWidth: 2,
                    pointBackgroundColor: '#FFFFFF',
                    pointBorderColor: '#8B0000',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    fill: true,
                    tension: 0.4 // Membuat garis melengkung smooth
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
                    data: [65, 15, 20], // Persentase Dummy
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
                cutout: '75%', // Membuat lubang tengah lebih besar
                plugins: {
                    legend: { display: false }, // Legend custom menggunakan HTML di atas
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
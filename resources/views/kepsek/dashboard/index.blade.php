@extends('layouts.app')

@section('title', 'Dashboard Executive')

@section('content')
<div class="space-y-6">
    
    <div class="bg-surface p-6 rounded-2xl shadow-sm border border-gray-100 flex justify-between items-center bg-gradient-to-r from-white to-gray-50">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Executive Dashboard</h2>
            <p class="text-sm text-gray-500 mt-1">Ringkasan performa finansial dan kepatuhan pembayaran siswa.</p>
        </div>
        <div class="hidden sm:flex h-12 w-12 rounded-full bg-primary/10 text-primary items-center justify-center text-xl">
            <i class="fa-solid fa-building-columns"></i>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <div class="bg-surface p-5 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden group hover:border-primary/50 transition">
            <div class="absolute -right-4 -top-4 w-16 h-16 bg-primary/5 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Total Pemasukan</p>
            <h3 class="text-xl font-black text-gray-800 relative z-10">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</h3>
            <p class="text-[10px] text-green-600 font-bold mt-2"><i class="fa-solid fa-arrow-trend-up mr-1"></i>All Time Record</p>
        </div>

        <div class="bg-surface p-5 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden group hover:border-green-500/50 transition">
            <div class="absolute -right-4 -top-4 w-16 h-16 bg-green-50 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Bulan Ini</p>
            <h3 class="text-xl font-black text-green-600 relative z-10">Rp {{ number_format($pemasukanBulanIni, 0, ',', '.') }}</h3>
            <p class="text-[10px] text-gray-500 mt-2">Pemasukan periode {{ date('F Y') }}</p>
        </div>

        <div class="bg-surface p-5 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden group hover:border-red-500/50 transition">
            <div class="absolute -right-4 -top-4 w-16 h-16 bg-red-50 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Total Tunggakan</p>
            <h3 class="text-xl font-black text-red-600 relative z-10">Rp {{ number_format($totalTunggakan, 0, ',', '.') }}</h3>
            <p class="text-[10px] text-gray-500 mt-2">Akumulasi tagihan belum lunas</p>
        </div>

        <div class="bg-surface p-5 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden group hover:border-blue-500/50 transition">
            <div class="absolute -right-4 -top-4 w-16 h-16 bg-blue-50 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Kepatuhan Siswa</p>
            <h3 class="text-xl font-black text-blue-600 relative z-10">{{ $totalSiswaLunas }} <span class="text-sm">Siswa</span></h3>
            <p class="text-[10px] text-gray-500 mt-2">Telah lunas bulan ini</p>
        </div>

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-2 bg-surface p-6 rounded-2xl shadow-sm border border-gray-100">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="font-bold text-gray-800">Tren Pemasukan {{ date('Y') }}</h3>
                    <p class="text-xs text-gray-500">Grafik pertumbuhan arus kas bulanan.</p>
                </div>
                <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center">
                    <i class="fa-solid fa-chart-area"></i>
                </div>
            </div>
            <div class="relative h-72">
                <canvas id="lineChart"></canvas>
            </div>
        </div>

        <div class="lg:col-span-1 bg-surface p-6 rounded-2xl shadow-sm border border-gray-100">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="font-bold text-gray-800">Status Tagihan</h3>
                    <p class="text-xs text-gray-500">Distribusi seluruh tagihan.</p>
                </div>
            </div>
            <div class="relative h-60 flex items-center justify-center">
                <canvas id="doughnutChart"></canvas>
            </div>
            <div class="mt-6 space-y-3">
                <div class="flex justify-between items-center text-sm">
                    <span class="flex items-center text-gray-600"><span class="w-3 h-3 rounded-full bg-green-500 mr-2"></span> Lunas</span>
                    <span class="font-bold text-gray-800">{{ $chartDoughnutData[0] ?? 0 }}</span>
                </div>
                <div class="flex justify-between items-center text-sm">
                    <span class="flex items-center text-gray-600"><span class="w-3 h-3 rounded-full bg-yellow-400 mr-2"></span> VA Aktif (Menunggu)</span>
                    <span class="font-bold text-gray-800">{{ $chartDoughnutData[1] ?? 0 }}</span>
                </div>
                <div class="flex justify-between items-center text-sm">
                    <span class="flex items-center text-gray-600"><span class="w-3 h-3 rounded-full bg-red-500 mr-2"></span> Belum Bayar/Nunggak</span>
                    <span class="font-bold text-gray-800">{{ $chartDoughnutData[2] ?? 0 }}</span>
                </div>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- 1. SETUP LINE CHART ---
        const lineCtx = document.getElementById('lineChart').getContext('2d');
        
        let gradientLine = lineCtx.createLinearGradient(0, 0, 0, 300);
        gradientLine.addColorStop(0, 'rgba(139, 0, 0, 0.5)'); // Primary Color with opacity
        gradientLine.addColorStop(1, 'rgba(139, 0, 0, 0.0)');

        new Chart(lineCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Pemasukan',
                    data: {!! json_encode($chartLineData) !!},
                    borderColor: '#8B0000',
                    backgroundColor: gradientLine,
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#8B0000',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
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
                                return ' Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [4, 4], color: '#f3f4f6' },
                        ticks: {
                            callback: function(value) { return 'Rp ' + (value/1000000) + 'M'; }
                        }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });

        // --- 2. SETUP DOUGHNUT CHART ---
        const doughnutCtx = document.getElementById('doughnutChart').getContext('2d');
        
        new Chart(doughnutCtx, {
            type: 'doughnut',
            data: {
                labels: ['Lunas', 'Menunggu', 'Belum Bayar'],
                datasets: [{
                   data: {!! json_encode($chartDoughnutData) !!},
                    backgroundColor: ['#22c55e', '#facc15', '#ef4444'], // Green, Yellow, Red
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%', // Membuat lubang tengah lebih besar
                plugins: {
                    legend: { display: false } 
                }
            }
        });
    });
</script>
@endpush
@endsection
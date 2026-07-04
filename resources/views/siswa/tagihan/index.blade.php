@extends('layouts.app')
@section('title', 'Tagihan Saya - SPP Digital')

@section('content')
    <div class="max-w-md mx-auto relative -mx-4 -mt-4 sm:mx-auto sm:mt-0 bg-gray-50 min-h-screen pb-24">

        <div
            class="bg-surface sticky top-0 z-30 px-5 py-4 flex items-center justify-between shadow-sm border-b border-gray-100">
            <div class="flex items-center gap-4">
                <a href="{{ route('siswa.dashboard') }}"
                    class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 hover:bg-gray-200 transition active:scale-95">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <h1 class="text-lg font-bold text-gray-800">Tagihan Saya</h1>
            </div>
            <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                <i class="fa-solid fa-file-invoice-dollar"></i>
            </div>
        </div>

        <div class="px-5 mt-5 space-y-4">

            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Riwayat Tagihan Bulanan</p>

            @forelse($tagihan as $t)
                <div class="bg-surface rounded-2xl p-5 shadow-sm border border-gray-100 relative overflow-hidden">

                    <div
                        class="absolute left-0 top-0 bottom-0 w-1.5 
                    {{ $t->status == 'lunas' ? 'bg-green-500' : ($t->status == 'menunggu' ? 'bg-yellow-500' : ($t->status == 'menunggak' ? 'bg-red-500' : 'bg-gray-300')) }}">
                    </div>

                    <div class="flex justify-between items-start mb-3 pl-2">
                        <div>
                            <h3 class="text-base font-bold text-gray-800 leading-tight">SPP Bulan {{ $t->bulan }}</h3>
                            <p class="text-xs text-gray-500 mt-0.5 font-medium">Tahun Ajaran {{ $t->tahun }}</p>
                        </div>

                        @if ($t->status == 'lunas')
                            <span
                                class="bg-green-100 text-green-700 px-2.5 py-1 rounded-md text-[10px] font-bold tracking-wide"><i
                                    class="fa-solid fa-check mr-1"></i>LUNAS</span>
                        @elseif($t->status == 'menunggu')
                            <span
                                class="bg-yellow-100 text-yellow-700 px-2.5 py-1 rounded-md text-[10px] font-bold tracking-wide"><i
                                    class="fa-solid fa-clock mr-1"></i>MENUNGGU</span>
                        @elseif($t->status == 'menunggak')
                            <span
                                class="bg-red-100 text-red-700 px-2.5 py-1 rounded-md text-[10px] font-bold tracking-wide"><i
                                    class="fa-solid fa-triangle-exclamation mr-1"></i>MENUNGGAK</span>
                        @else
                            <span
                                class="bg-gray-100 text-gray-600 px-2.5 py-1 rounded-md text-[10px] font-bold tracking-wide">BELUM
                                BAYAR</span>
                        @endif
                    </div>

                    <div class="bg-gray-50 rounded-xl p-3 mb-4 pl-4 border border-gray-100">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-xs font-medium text-gray-500">Nominal Tagihan</span>
                            <span class="text-sm font-black text-primary">Rp
                                {{ number_format($t->nominal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-medium text-gray-500">Batas Pembayaran</span>
                            <span
                                class="text-xs font-bold {{ $t->jatuh_tempo < now() && $t->status != 'lunas' ? 'text-red-500' : 'text-gray-800' }}">
                                {{ \Carbon\Carbon::parse($t->jatuh_tempo)->translatedFormat('d F Y') }}
                            </span>
                        </div>
                    </div>

                    <div class="pl-2">
                        @if ($t->status == 'lunas')
                            <button
                                class="w-full bg-green-50 text-green-600 border border-green-200 py-2.5 rounded-xl text-sm font-bold flex justify-center items-center gap-2 cursor-not-allowed">
                                <i class="fa-solid fa-circle-check"></i> Pembayaran Selesai
                            </button>
                        @elseif($t->status == 'menunggu' && $t->pembayaranAktif)
                            <button
                                onclick="openModalInstruksi('{{ strtoupper($t->pembayaranAktif->bank) }}', '{{ $t->pembayaranAktif->va_number }}', 'Rp {{ number_format($t->nominal, 0, ',', '.') }}')"
                                class="w-full block text-center bg-yellow-500 hover:bg-yellow-600 text-white py-2.5 rounded-xl text-sm font-bold shadow-sm transition active:scale-95 flex items-center justify-center">
                                <i class="fa-solid fa-wallet mr-2"></i> Lihat Instruksi Bayar
                            </button>
                        @else
                            <a href="#"
                                class="w-full block text-center bg-primary hover:bg-primary_hover text-white py-2.5 rounded-xl text-sm font-bold shadow-md shadow-primary/30 transition active:scale-95">
                                <i class="fa-solid fa-money-bill-wave mr-1"></i> Bayar Sekarang
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div
                    class="bg-surface rounded-2xl p-8 shadow-sm border border-gray-100 text-center flex flex-col items-center mt-10">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                        <i class="fa-solid fa-box-open text-gray-300 text-3xl"></i>
                    </div>
                    <h3 class="font-bold text-gray-800 mb-1">Yeay! Tidak ada tagihan</h3>
                    <p class="text-xs font-medium text-gray-500">Saat ini kamu belum memiliki tagihan SPP yang harus
                        dibayar.</p>
                </div>
            @endforelse

        </div>
    </div>
    <div id="modal-instruksi" class="fixed inset-0 z-[100] hidden bg-gray-900/60 backdrop-blur-sm flex items-end sm:items-center justify-center transition-opacity p-0 sm:p-4">
    <div class="bg-surface w-full max-w-md rounded-t-3xl sm:rounded-2xl shadow-2xl overflow-hidden transform transition-all transform translate-y-0 relative">
        
        <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="text-lg font-bold text-gray-800">Instruksi Pembayaran</h3>
            <button onclick="closeModalInstruksi()" class="w-8 h-8 rounded-full bg-gray-200 text-gray-500 hover:bg-red-100 hover:text-red-500 flex items-center justify-center transition">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="p-6 space-y-6">
            
            <div class="text-center">
                <p class="text-sm text-gray-500 font-medium mb-1">Total Tagihan</p>
                <h2 id="ins-nominal" class="text-3xl font-black text-primary">Rp 0</h2>
            </div>

            <div class="bg-blue-50/50 border border-blue-100 rounded-2xl p-5 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-16 h-16 bg-blue-100 rounded-bl-full opacity-50"></div>
                
                <p class="text-xs font-bold text-blue-600 uppercase tracking-wider mb-2 relative z-10" id="ins-bank">BANK VIRTUAL ACCOUNT</p>
                
                <div class="mb-4 relative z-10">
                    <p id="ins-va" class="text-2xl sm:text-3xl font-mono font-black text-gray-800 tracking-widest break-all">0000000000</p>
                </div>
                
                <button onclick="copyVA()" class="w-full relative z-10 bg-white border border-blue-200 text-blue-600 hover:bg-blue-600 hover:text-white py-2.5 rounded-xl text-sm font-bold shadow-sm transition active:scale-95 flex items-center justify-center gap-2" title="Salin Nomor VA">
                    <i id="copy-icon" class="fa-regular fa-copy"></i> <span id="copy-text">Salin Nomor VA</span>
                </button>
            </div>

            <div>
                <h4 class="text-sm font-bold text-gray-800 mb-3">Cara Pembayaran (M-Banking / ATM)</h4>
                <ul class="space-y-3">
                    <li class="flex items-start gap-3">
                        <span class="w-6 h-6 rounded-full bg-gray-100 text-gray-600 text-xs font-bold flex items-center justify-center shrink-0 mt-0.5">1</span>
                        <p class="text-sm text-gray-600 leading-snug">Buka aplikasi Mobile Banking atau kunjungi ATM terdekat.</p>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="w-6 h-6 rounded-full bg-gray-100 text-gray-600 text-xs font-bold flex items-center justify-center shrink-0 mt-0.5">2</span>
                        <p class="text-sm text-gray-600 leading-snug">Pilih menu <strong>Transfer</strong> > <strong>Virtual Account</strong>.</p>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="w-6 h-6 rounded-full bg-gray-100 text-gray-600 text-xs font-bold flex items-center justify-center shrink-0 mt-0.5">3</span>
                        <p class="text-sm text-gray-600 leading-snug">Masukkan nomor Virtual Account yang tertera di atas.</p>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="w-6 h-6 rounded-full bg-gray-100 text-gray-600 text-xs font-bold flex items-center justify-center shrink-0 mt-0.5">4</span>
                        <p class="text-sm text-gray-600 leading-snug">Periksa detail pembayaran, lalu konfirmasi dengan PIN Anda.</p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    // Referensi elemen Modal
    const modalInstruksi = document.getElementById('modal-instruksi');
    const txtBank = document.getElementById('ins-bank');
    const txtVA = document.getElementById('ins-va');
    const txtNominal = document.getElementById('ins-nominal');
    const copyIcon = document.getElementById('copy-icon');

    // Fungsi Buka Modal & Set Data
    function openModalInstruksi(bank, vaNumber, nominal) {
        // Set teks
        txtBank.innerText = bank + " VIRTUAL ACCOUNT";
        txtVA.innerText = vaNumber;
        txtNominal.innerText = nominal;
        
        // Reset icon copy
        copyIcon.className = "fa-regular fa-copy";
        
        // Tampilkan Modal
        modalInstruksi.classList.remove('hidden');
    }

    // Fungsi Tutup Modal
    function closeModalInstruksi() {
        modalInstruksi.classList.add('hidden');
    }

    // Menutup modal jika area gelap diklik
    modalInstruksi.addEventListener('click', function(e) {
        if(e.target === this) {
            closeModalInstruksi();
        }
    });

    // Fungsi Copy to Clipboard
    function copyVA() {
        // Ambil teks VA
        const vaNumber = txtVA.innerText;
        const copyText = document.getElementById('copy-text');
        
        // Copy ke clipboard menggunakan API navigator modern
        navigator.clipboard.writeText(vaNumber).then(() => {
            // Ubah icon jadi centang
            copyIcon.className = "fa-solid fa-check";
            copyText.innerText = "Tersalin!";
            
            // Tampilkan SweetAlert mini (Toast)
            Swal.fire({
                toast: true,
                position: 'top',
                icon: 'success',
                title: 'Nomor VA berhasil disalin!',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
            });
            
            // Kembalikan icon setelah 2 detik
            setTimeout(() => {
                copyIcon.className = "fa-regular fa-copy";
                copyText.innerText = "Salin Nomor VA";
            }, 2000);
        }).catch(err => {
            console.error('Gagal menyalin teks: ', err);
        });
    }
</script>
@endsection

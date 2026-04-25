@extends('layouts.app')
@section('title', 'Virtual Account & Pembayaran')

@section('content')
<div class="bg-surface p-4 md:p-6 rounded-2xl shadow-sm border border-gray-100">
    
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Manajemen VA & Pembayaran</h2>
            <p class="text-sm text-gray-500 mt-1">Generate Virtual Account (VA) untuk tagihan siswa.</p>
        </div>
    </div>

    <div class="overflow-x-auto pb-24"> 
        <table class="w-full text-left border-collapse min-w-max">
            <thead class="bg-gray-50 text-gray-500 text-sm border-y border-gray-100">
                <tr>
                    <th class="py-3 px-4 font-semibold">Siswa</th>
                    <th class="py-3 px-4 font-semibold">Bulan</th>
                    <th class="py-3 px-4 font-semibold">Nominal</th>
                    <th class="py-3 px-4 font-semibold">Status Tagihan</th>
                    <th class="py-3 px-4 font-semibold">Virtual Account</th>
                    <th class="py-3 px-4 font-semibold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-700">
                @forelse($tagihan as $t)
                <tr class="border-b border-gray-50 hover:bg-gray-50 transition-colors">
                    
                    <td class="py-3 px-4">
                        <p class="font-bold text-gray-900">{{ $t->siswa->nama }}</p>
                        <p class="text-xs text-gray-500">{{ $t->siswa->kelas->nama_kelas ?? '-' }} • NISN: {{ $t->siswa->nisn }}</p>
                    </td>
                    
                    <td class="py-3 px-4 font-medium text-gray-800">{{ $t->bulan }} {{ $t->tahun }}</td>
                    
                    <td class="py-3 px-4 font-bold text-primary">Rp {{ number_format($t->nominal, 0, ',', '.') }}</td>
                    
                    <td class="py-3 px-4">
                        @if($t->status == 'lunas')
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-md text-xs font-bold border border-green-200"><i class="fa-solid fa-check-circle mr-1"></i>Lunas</span>
                        @elseif($t->status == 'menunggu')
                            <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-md text-xs font-bold border border-yellow-200"><i class="fa-solid fa-clock mr-1"></i>Menunggu</span>
                        @elseif($t->status == 'menunggak')
                            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-md text-xs font-bold border border-red-200"><i class="fa-solid fa-triangle-exclamation mr-1"></i>Menunggak</span>
                        @else
                            <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-md text-xs font-bold border border-gray-200">Belum Bayar</span>
                        @endif
                    </td>

                    <td class="py-3 px-4">
                        @if($t->pembayaranAktif)
                            <div class="flex flex-col">
                                <span class="text-xs font-bold uppercase text-gray-500">{{ $t->pembayaranAktif->bank }} VIRTUAL ACCOUNT</span>
                                <span class="font-mono text-sm font-bold text-gray-800 bg-gray-100 px-2 py-1 rounded mt-1 inline-block w-max">{{ $t->pembayaranAktif->va_number }}</span>
                            </div>
                        @else
                            <span class="text-gray-400 text-xs italic">Belum ada VA aktif</span>
                        @endif
                    </td>

                    <td class="py-3 px-4 text-center">
                        @if($t->status == 'lunas')
                            <button disabled class="text-gray-400 bg-gray-100 px-3 py-1.5 rounded-md text-xs font-bold cursor-not-allowed">Selesai</button>
                        
                        @elseif($t->status == 'menunggu' && $t->pembayaranAktif)
                            <form action="{{ route('admin.pembayaran.cek_status', $t->pembayaranAktif->order_id) }}" method="POST" class="inline-block">
                                @csrf
                                <button type="submit" class="bg-white border border-primary text-primary hover:bg-primary hover:text-white px-3 py-1.5 rounded-md text-xs font-bold transition-colors">
                                    <i class="fa-solid fa-rotate mr-1"></i> Cek Status
                                </button>
                            </form>

                        @else
                            <button onclick="openModalVA({{ $t->id }}, '{{ $t->siswa->nama }}', '{{ $t->bulan }}')" class="bg-secondary hover:bg-yellow-500 text-white px-3 py-1.5 rounded-md text-xs font-bold transition-colors shadow-sm">
                                <i class="fa-solid fa-plus mr-1"></i> {{ $t->status == 'gagal' ? 'Re-Generate VA' : 'Generate VA' }}
                            </button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-6 text-gray-500">Belum ada data tagihan. Generate dari menu Tagihan SPP terlebih dahulu.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div id="modal-va" class="fixed inset-0 z-[100] hidden bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity">
    <div class="bg-surface w-full max-w-sm rounded-2xl shadow-xl overflow-hidden transform transition-all">
        
        <div class="bg-primary p-5 text-center">
            <h3 class="text-lg font-bold text-white">Buat Virtual Account</h3>
            <p class="text-sm text-primary-100 mt-1" id="va-siswa-name">Nama Siswa</p>
        </div>

        <form id="form-va" action="" method="POST" onsubmit="showLoading()">
            @csrf
            <div class="p-5 space-y-4">
                <p class="text-sm text-gray-600 text-center mb-4">Silakan pilih bank untuk Generate VA tagihan bulan <strong id="va-bulan"></strong>.</p>
                
                <div class="grid grid-cols-1 gap-3">
                    <label class="cursor-pointer">
                        <input type="radio" name="bank" value="bca" class="peer sr-only" required>
                        <div class="rounded-lg border border-gray-200 bg-white p-4 hover:bg-gray-50 peer-checked:border-primary peer-checked:bg-primary/5 transition-all flex justify-between items-center">
                            <span class="font-bold text-gray-800">BCA</span>
                            <span class="text-primary hidden peer-checked:block"><i class="fa-solid fa-circle-check"></i></span>
                        </div>
                    </label>

                    <label class="cursor-pointer">
                        <input type="radio" name="bank" value="bni" class="peer sr-only" required>
                        <div class="rounded-lg border border-gray-200 bg-white p-4 hover:bg-gray-50 peer-checked:border-primary peer-checked:bg-primary/5 transition-all flex justify-between items-center">
                            <span class="font-bold text-gray-800">BNI</span>
                            <span class="text-primary hidden peer-checked:block"><i class="fa-solid fa-circle-check"></i></span>
                        </div>
                    </label>

                    <label class="cursor-pointer">
                        <input type="radio" name="bank" value="bri" class="peer sr-only" required>
                        <div class="rounded-lg border border-gray-200 bg-white p-4 hover:bg-gray-50 peer-checked:border-primary peer-checked:bg-primary/5 transition-all flex justify-between items-center">
                            <span class="font-bold text-gray-800">BRI</span>
                            <span class="text-primary hidden peer-checked:block"><i class="fa-solid fa-circle-check"></i></span>
                        </div>
                    </label>
                </div>
            </div>

            <div class="flex justify-end gap-3 p-5 border-t border-gray-100 bg-gray-50">
                <button type="button" onclick="closeModal('modal-va')" class="w-full px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Batal</button>
                <button type="submit" class="w-full px-5 py-2 text-sm font-medium text-white bg-primary hover:bg-primary_hover rounded-lg transition-colors shadow-sm">Generate Sekarang</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModalVA(tagihanId, namaSiswa, bulan) {
        document.getElementById('modal-va').classList.remove('hidden');
        document.getElementById('va-siswa-name').innerText = namaSiswa;
        document.getElementById('va-bulan').innerText = bulan;
        
        // Ubah action form ke route yg sesuai
        document.getElementById('form-va').action = `/admin/pembayaran/generate-va/${tagihanId}`;
    }

    function closeModal(id) { 
        document.getElementById(id).classList.add('hidden'); 
    }

    function showLoading() {
        closeModal('modal-va');
        if(document.getElementById('loading-screen')) {
            document.getElementById('loading-screen').style.display = 'flex';
            document.getElementById('loading-screen').style.opacity = '1';
        }
    }
</script>
@endsection
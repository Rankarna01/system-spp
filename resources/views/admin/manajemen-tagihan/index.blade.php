@extends('layouts.app')
@section('title', 'Manajemen Tagihan')

@section('content')
<div class="space-y-6">

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-surface rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium">Tahun Ajaran Aktif</p>
                <h3 class="text-xl font-bold text-gray-800 mt-1">{{ $activeTahun->tahun ?? 'Tidak Ada' }}</h3>
            </div>
            <div class="h-12 w-12 rounded-full bg-primary/10 text-primary flex items-center justify-center text-xl">
                <i class="fa-solid fa-calendar-check"></i>
            </div>
        </div>
        <div class="bg-surface rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium">Total Template (Master)</p>
                <h3 class="text-xl font-bold text-gray-800 mt-1">{{ $masters->count() }} Template</h3>
            </div>
            <div class="h-12 w-12 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-file-contract"></i>
            </div>
        </div>
        <div class="bg-surface rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium">Tagihan Diterbitkan</p>
                <h3 class="text-xl font-bold text-gray-800 mt-1">{{ number_format($totalTagihan) }} Invoice</h3>
            </div>
            <div class="h-12 w-12 rounded-full bg-green-50 text-green-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-file-invoice-dollar"></i>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-surface p-5 rounded-2xl border border-gray-100 shadow-sm">
            <div class="flex justify-between items-center mb-5">
                <h2 class="text-lg font-bold text-gray-800">Master SPP (Bulan Ini)</h2>
                <button onclick="openModalMaster()" class="bg-primary hover:bg-primary_hover text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center">
                    <i class="fa-solid fa-plus mr-2"></i> Buat Master
                </button>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-gray-500 border-y border-gray-100">
                        <tr>
                            <th class="py-3 px-4 font-semibold">Bulan</th>
                            <th class="py-3 px-4 font-semibold">Nominal</th>
                            <th class="py-3 px-4 font-semibold">Jatuh Tempo</th>
                            <th class="py-3 px-4 font-semibold text-center">Aksi (Generate)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $namaBulan = [1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April', 5=>'Mei', 6=>'Juni', 7=>'Juli', 8=>'Agustus', 9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember']; @endphp
                        @forelse($masters as $m)
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="py-3 px-4 font-bold text-gray-800">{{ $namaBulan[$m->bulan] }}</td>
                            <td class="py-3 px-4 font-medium text-primary">Rp {{ number_format($m->nominal, 0, ',', '.') }}</td>
                            <td class="py-3 px-4 text-gray-600">{{ \Carbon\Carbon::parse($m->tanggal_jatuh_tempo)->translatedFormat('d F Y') }}</td>
                            <td class="py-3 px-4 text-center flex items-center justify-center gap-2">
                                <button onclick="openModalGenerate({{ $m->id }}, '{{ $namaBulan[$m->bulan] }}')" class="bg-secondary hover:bg-yellow-500 text-white px-3 py-1.5 rounded-md text-xs font-bold transition-colors">
                                    <i class="fa-solid fa-bolt mr-1"></i> Generate
                                </button>
                                <form action="{{ route('admin.tagihan.master.destroy', $m->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus master tagihan bulan {{ $namaBulan[$m->bulan] }} ini? (Semua tagihan siswa yang terkait juga akan ikut terhapus)')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded-md text-xs font-bold transition-colors">
                                        <i class="fa-solid fa-trash mr-1"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-6 text-gray-500">Belum ada master tagihan untuk tahun ajaran ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="lg:col-span-1 bg-surface p-5 rounded-2xl border border-gray-100 shadow-sm">
            <h2 class="text-lg font-bold text-gray-800 mb-5">Riwayat Generate Tagihan</h2>
            <div class="space-y-4">
                @forelse($logs as $log)
                <div class="flex gap-3 items-start border-b border-gray-50 pb-3">
                    <div class="mt-1 h-8 w-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-800 font-medium">{{ $log->keterangan }}</p>
                        <p class="text-xs text-gray-400 mt-1"><i class="fa-regular fa-clock"></i> {{ $log->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @empty
                <p class="text-sm text-gray-500 text-center py-4">Belum ada aktivitas generate.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div id="modal-master" class="fixed inset-0 z-[100] hidden bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-surface w-full max-w-md rounded-2xl shadow-xl overflow-hidden">
        <div class="flex justify-between items-center p-5 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-800">Buat Template Master SPP</h3>
            <button onclick="closeModal('modal-master')" class="text-gray-400 hover:text-red-500"><i class="fa-solid fa-xmark text-xl"></i></button>
        </div>
        <form action="{{ route('admin.tagihan.master.store') }}" method="POST">
            @csrf
            <div class="p-5 space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Pilih Bulan</label>
                    <select name="bulan" class="w-full border rounded-lg px-4 py-2 focus:ring-primary outline-none" required>
                        <option value="">-- Pilih Bulan --</option>
                        @for($i=1; $i<=12; $i++)
                            <option value="{{ $i }}">{{ $namaBulan[$i] }}</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nominal SPP (Rp)</label>
                    <input type="number" name="nominal" class="w-full border rounded-lg px-4 py-2 focus:ring-primary outline-none" placeholder="Contoh: 150000" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Jatuh Tempo</label>
                    <input type="date" name="tanggal_jatuh_tempo" class="w-full border rounded-lg px-4 py-2 focus:ring-primary outline-none" required>
                </div>
            </div>
            <div class="flex justify-end gap-3 p-5 border-t border-gray-100 bg-gray-50">
                <button type="button" onclick="closeModal('modal-master')" class="px-4 py-2 text-sm text-gray-700 border rounded-lg hover:bg-gray-50">Batal</button>
                <button type="submit" class="px-5 py-2 text-sm text-white bg-primary hover:bg-primary_hover rounded-lg">Simpan Master</button>
            </div>
        </form>
    </div>
</div>

<div id="modal-generate" class="fixed inset-0 z-[100] hidden bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-surface w-full max-w-md rounded-2xl shadow-xl overflow-hidden border-t-4 border-secondary">
        <div class="p-5 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 mb-4">
                <i class="fa-solid fa-bolt text-2xl text-secondary"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-800">Generate Tagihan <span id="text-bulan-generate" class="text-primary"></span></h3>
            <p class="text-sm text-gray-500 mt-2">Pilih target siswa yang akan diberikan tagihan ini.</p>
        </div>
        
        <form action="{{ route('admin.tagihan.generate') }}" method="POST" onsubmit="showLoading()">
            @csrf
            <input type="hidden" name="spp_master_id" id="input_master_id">
            
            <div class="px-5 pb-5 space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Target Generate</label>
                    <select name="tipe" id="tipe_generate" onchange="toggleKelasSelect()" class="w-full border rounded-lg px-4 py-2 focus:ring-primary outline-none bg-gray-50" required>
                        <option value="semua">Semua Siswa Aktif</option>
                        <option value="kelas">Pilih Per Kelas</option>
                    </select>
                </div>
                
                <div id="div_kelas" class="hidden">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Kelas</label>
                    <select name="kelas_id" id="kelas_id" class="w-full border rounded-lg px-4 py-2 focus:ring-primary outline-none">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex justify-end gap-3 p-5 border-t border-gray-100 bg-gray-50">
                <button type="button" onclick="closeModal('modal-generate')" class="px-4 py-2 text-sm text-gray-700 border rounded-lg hover:bg-gray-50">Batal</button>
                <button type="submit" class="px-5 py-2 text-sm text-white bg-secondary hover:bg-yellow-600 rounded-lg font-bold shadow-md"><i class="fa-solid fa-paper-plane mr-2"></i> Eksekusi Generate!</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModalMaster() { document.getElementById('modal-master').classList.remove('hidden'); }
    
    function openModalGenerate(masterId, namaBulan) {
        document.getElementById('modal-generate').classList.remove('hidden');
        document.getElementById('input_master_id').value = masterId;
        document.getElementById('text-bulan-generate').innerText = namaBulan;
    }

    function closeModal(id) { document.getElementById(id).classList.add('hidden'); }

    // Logic memunculkan pilihan kelas jika tipe="kelas"
    function toggleKelasSelect() {
        const val = document.getElementById('tipe_generate').value;
        const divKelas = document.getElementById('div_kelas');
        const inputKelas = document.getElementById('kelas_id');
        
        if(val === 'kelas') {
            divKelas.classList.remove('hidden');
            inputKelas.required = true;
        } else {
            divKelas.classList.add('hidden');
            inputKelas.required = false;
        }
    }

    // Memunculkan Loading Screen bawaan layout saat proses generate berat berjalan
    function showLoading() {
        document.getElementById('loading-screen').style.display = 'flex';
        document.getElementById('loading-screen').style.opacity = '1';
    }
</script>
@endsection
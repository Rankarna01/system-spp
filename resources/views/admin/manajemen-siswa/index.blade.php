@extends('layouts.app')

@section('title', 'Data Siswa')

@section('content')
<div class="bg-surface p-4 md:p-6 rounded-2xl shadow-sm border border-gray-100">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Manajemen Data Siswa</h2>
            <p class="text-sm text-gray-500 mt-1">Kelola data seluruh siswa beserta kelas dan tahun ajarannya.</p>
        </div>
        <button onclick="openModalTambah()" class="w-full sm:w-auto bg-primary hover:bg-primary_hover text-white px-4 py-2.5 rounded-lg text-sm font-medium transition-colors shadow-sm flex items-center justify-center">
            <i class="fa-solid fa-user-plus mr-2"></i> Tambah Siswa Baru
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse min-w-max">
            <thead class="bg-gray-50 text-gray-500 text-sm border-y border-gray-100">
                <tr>
                    <th class="py-3 px-4 font-semibold w-12">No</th>
                    <th class="py-3 px-4 font-semibold">NISN</th>
                    <th class="py-3 px-4 font-semibold">Nama Siswa</th>
                    <th class="py-3 px-4 font-semibold">Kelas</th>
                    <th class="py-3 px-4 font-semibold">Jurusan</th>
                    <th class="py-3 px-4 font-semibold">Tahun Ajaran</th>
                    <th class="py-3 px-4 font-semibold text-center w-32">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-700">
                @forelse($data as $key => $item)
                <tr class="border-b border-gray-50 hover:bg-gray-50 transition-colors">
                    <td class="py-3 px-4">{{ $key + 1 }}</td>
                    <td class="py-3 px-4 font-mono text-gray-600">{{ $item->nisn }}</td>
                    <td class="py-3 px-4 font-medium text-gray-900">{{ $item->nama }}</td>
                    <td class="py-3 px-4">{{ $item->kelas->nama_kelas ?? '-' }}</td>
                    <td class="py-3 px-4">
                        <span class="bg-blue-50 text-blue-700 px-2.5 py-1 rounded-md text-xs font-semibold">
                            {{ $item->kelas->jurusan->nama_jurusan ?? '-' }}
                        </span>
                    </td>
                    <td class="py-3 px-4 text-gray-600">{{ $item->tahunAjaran->tahun ?? '-' }}</td>
                    <td class="py-3 px-4 flex justify-center gap-2">
                        <button onclick="openModalEdit({{ $item->id }}, '{{ $item->nisn }}', '{{ $item->nama }}', '{{ $item->kelas_id }}', '{{ $item->tahun_ajaran_id }}')" class="text-blue-600 hover:text-blue-800 bg-blue-50 p-2 rounded-md transition-colors" title="Edit Data">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <form action="{{ route('admin.siswa.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data siswa ini?')">
                            @csrf 
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 bg-red-50 p-2 rounded-md transition-colors" title="Hapus Data">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-8 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fa-solid fa-users-slash text-4xl text-gray-300 mb-3"></i>
                            <p>Belum ada data siswa terdaftar.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div id="modal-form" class="fixed inset-0 z-[100] hidden bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity overflow-y-auto">
    <div class="bg-surface w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden transform transition-all my-8">
        
        <div class="flex justify-between items-center p-5 border-b border-gray-100 bg-gray-50/50">
            <h3 id="modal-title" class="text-lg font-bold text-gray-800">Tambah Siswa Baru</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-red-500 transition-colors">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>

        <form id="form-siswa" action="{{ route('admin.siswa.store') }}" method="POST">
            @csrf
            <input type="hidden" name="_method" id="method-type" value="POST">
            
            <div class="p-5 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="nisn" class="block text-sm font-semibold text-gray-700 mb-1">NISN <span class="text-red-500">*</span></label>
                        <input type="text" name="nisn" id="nisn" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm font-mono" placeholder="Masukkan NISN" required autocomplete="off">
                    </div>
                    <div>
                        <label for="nama" class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="nama" id="nama" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm" placeholder="Nama Siswa" required autocomplete="off">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="kelas_id" class="block text-sm font-semibold text-gray-700 mb-1">Kelas <span class="text-red-500">*</span></label>
                        <select name="kelas_id" id="kelas_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm bg-white" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelas as $k)
                                <option value="{{ $k->id }}">{{ $k->nama_kelas }} ({{ $k->jurusan->nama_jurusan ?? '-' }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="tahun_ajaran_id" class="block text-sm font-semibold text-gray-700 mb-1">Tahun Ajaran <span class="text-red-500">*</span></label>
                        <select name="tahun_ajaran_id" id="tahun_ajaran_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm bg-white" required>
                            <option value="">-- Pilih Tahun Ajaran --</option>
                            @foreach($tahun as $t)
                                <option value="{{ $t->id }}">{{ $t->tahun }} {{ $t->is_active ? '(Aktif)' : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 p-5 border-t border-gray-100 bg-gray-50/50">
                <button type="button" onclick="closeModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Batal</button>
                <button type="submit" id="btn-submit" class="px-5 py-2 text-sm font-medium text-white bg-primary hover:bg-primary_hover rounded-lg transition-colors shadow-sm flex items-center">
                    <i class="fa-solid fa-floppy-disk mr-2"></i> Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const modal = document.getElementById('modal-form');
    const form = document.getElementById('form-siswa');
    const modalTitle = document.getElementById('modal-title');
    const methodType = document.getElementById('method-type');
    const btnSubmit = document.getElementById('btn-submit');

    // Deklarasi Input
    const inputNisn = document.getElementById('nisn');
    const inputNama = document.getElementById('nama');
    const inputKelas = document.getElementById('kelas_id');
    const inputTahun = document.getElementById('tahun_ajaran_id');

    function openModalTambah() { 
        modal.classList.remove('hidden'); 
        form.reset(); 
        
        form.action = "{{ route('admin.siswa.store') }}";
        methodType.value = "POST";
        
        modalTitle.innerText = "Tambah Siswa Baru";
        btnSubmit.innerHTML = '<i class="fa-solid fa-floppy-disk mr-2"></i> Simpan Data';
        
        setTimeout(() => inputNisn.focus(), 100);
    }
    
    function openModalEdit(id, nisn, nama, kelas_id, tahun_ajaran_id) {
        modal.classList.remove('hidden');
        
        form.action = `/admin/manajemen-siswa/siswa/${id}`;
        methodType.value = "PUT";
        
        // Mengisi form dengan data lama
        inputNisn.value = nisn;
        inputNama.value = nama;
        inputKelas.value = kelas_id;
        inputTahun.value = tahun_ajaran_id;
        
        modalTitle.innerText = "Edit Data Siswa";
        btnSubmit.innerHTML = '<i class="fa-solid fa-check mr-2"></i> Update Data';
    }

    function closeModal() { 
        modal.classList.add('hidden'); 
    }

    modal.addEventListener('click', function(e) {
        if(e.target === this) { closeModal(); }
    });
</script>
@endsection
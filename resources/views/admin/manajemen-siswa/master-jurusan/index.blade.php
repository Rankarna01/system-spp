@extends('layouts.app')

@section('title', 'Master Jurusan')

@section('content')
<div class="bg-surface p-4 md:p-6 rounded-2xl shadow-sm border border-gray-100">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Data Master Jurusan</h2>
            <p class="text-sm text-gray-500 mt-1">Kelola data jurusan yang ada di sekolah.</p>
        </div>
        <button onclick="openModalTambah()" class="w-full sm:w-auto bg-primary hover:bg-primary_hover text-white px-4 py-2.5 rounded-lg text-sm font-medium transition-colors shadow-sm flex items-center justify-center">
            <i class="fa-solid fa-plus mr-2"></i> Tambah Jurusan
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse min-w-max">
            <thead class="bg-gray-50 text-gray-500 text-sm border-y border-gray-100">
                <tr>
                    <th class="py-3 px-4 font-semibold w-16">No</th>
                    <th class="py-3 px-4 font-semibold">Nama Jurusan</th>
                    <th class="py-3 px-4 font-semibold text-center w-32">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-700">
                @forelse($data as $key => $item)
                <tr class="border-b border-gray-50 hover:bg-gray-50 transition-colors">
                    <td class="py-3 px-4">{{ $key + 1 }}</td>
                    <td class="py-3 px-4 font-medium text-gray-900">{{ $item->nama_jurusan }}</td>
                    <td class="py-3 px-4 flex justify-center gap-3">
                        <button onclick="openModalEdit({{ $item->id }}, '{{ $item->nama_jurusan }}')" class="text-blue-600 hover:text-blue-800 bg-blue-50 p-2 rounded-md transition-colors" title="Edit Data">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        
                        <form action="{{ route('admin.jurusan.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jurusan ini? Data kelas & siswa yang terkait mungkin akan terpengaruh.')">
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
                    <td colspan="3" class="py-8 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fa-solid fa-folder-open text-4xl text-gray-300 mb-3"></i>
                            <p>Belum ada data jurusan. Silakan tambah data baru.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div id="modal-form" class="fixed inset-0 z-[100] hidden bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity">
    <div class="bg-surface w-full max-w-md rounded-2xl shadow-2xl overflow-hidden transform transition-all">
        
        <div class="flex justify-between items-center p-5 border-b border-gray-100 bg-gray-50/50">
            <h3 id="modal-title" class="text-lg font-bold text-gray-800">Tambah Jurusan Baru</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-red-500 transition-colors">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>

        <form id="form-jurusan" action="{{ route('admin.jurusan.store') }}" method="POST">
            @csrf
            <input type="hidden" name="_method" id="method-type" value="POST">
            
            <div class="p-5">
                <div class="mb-2">
                    <label for="nama_jurusan" class="block text-sm font-semibold text-gray-700 mb-2">Nama Jurusan <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_jurusan" id="nama_jurusan" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm" placeholder="Contoh: Rekayasa Perangkat Lunak" required autocomplete="off">
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
    const form = document.getElementById('form-jurusan');
    const modalTitle = document.getElementById('modal-title');
    const methodType = document.getElementById('method-type');
    const inputNama = document.getElementById('nama_jurusan');
    const btnSubmit = document.getElementById('btn-submit');

    // Fungsi Buka Modal Tambah Data
    function openModalTambah() { 
        modal.classList.remove('hidden'); 
        form.reset(); // Bersihkan inputan
        
        // Kembalikan form ke mode POST (Tambah)
        form.action = "{{ route('admin.jurusan.store') }}";
        methodType.value = "POST";
        
        // Ubah Teks UI
        modalTitle.innerText = "Tambah Jurusan Baru";
        btnSubmit.innerHTML = '<i class="fa-solid fa-floppy-disk mr-2"></i> Simpan Data';
        
        // Auto focus ke inputan setelah 100ms
        setTimeout(() => inputNama.focus(), 100);
    }
    
    // Fungsi Buka Modal Edit Data
    function openModalEdit(id, nama) {
        modal.classList.remove('hidden');
        
        // Ubah form ke mode PUT (Update)
        // Karena kita pakai route prefix 'admin/manajemen-siswa', URL-nya mengarah ke sana
        form.action = `/admin/manajemen-siswa/jurusan/${id}`;
        methodType.value = "PUT";
        
        // Isi inputan dengan data yang mau diedit
        inputNama.value = nama;
        
        // Ubah Teks UI
        modalTitle.innerText = "Edit Data Jurusan";
        btnSubmit.innerHTML = '<i class="fa-solid fa-check mr-2"></i> Update Data';
    }

    // Fungsi Tutup Modal
    function closeModal() { 
        modal.classList.add('hidden'); 
    }

    // Menutup modal jika area gelap di luar kotak modal di-klik
    modal.addEventListener('click', function(e) {
        if(e.target === this) {
            closeModal();
        }
    });
</script>
@endsection
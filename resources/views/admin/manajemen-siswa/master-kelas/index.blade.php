@extends('layouts.app')
@section('title', 'Master Kelas')

@section('content')
<div class="bg-surface p-6 rounded-2xl shadow-sm">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-gray-800">Data Master Kelas</h2>
        <button onclick="openModal('modal-form')" class="bg-primary hover:bg-primary_hover text-white px-4 py-2 rounded-lg text-sm">
            <i class="fa-solid fa-plus mr-2"></i> Tambah Kelas
        </button>
    </div>

    <table class="w-full text-left border-collapse">
        <thead class="bg-gray-50 text-gray-500 text-sm">
            <tr>
                <th class="p-4 border-b">No</th>
                <th class="p-4 border-b">Nama Kelas</th>
                <th class="p-4 border-b">Jurusan</th>
                <th class="p-4 border-b text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $key => $item)
            <tr class="border-b hover:bg-gray-50">
                <td class="p-4">{{ $key + 1 }}</td>
                <td class="p-4 font-semibold">{{ $item->nama_kelas }}</td>
                <td class="p-4">{{ $item->jurusan->nama_jurusan ?? '-' }}</td>
                <td class="p-4 flex justify-center gap-3">
                    <button onclick="editData({{ $item->id }}, '{{ $item->nama_kelas }}', {{ $item->jurusan_id }})" class="text-blue-500 hover:text-blue-700">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                    <form action="{{ route('admin.kelas.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin hapus data ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700"><i class="fa-solid fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div id="modal-form" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center">
    <div class="bg-surface w-full max-w-md p-6 rounded-2xl shadow-xl">
        <h3 id="modal-title" class="text-lg font-bold mb-4">Tambah Kelas</h3>
        <form id="form-kelas" action="{{ route('admin.kelas.store') }}" method="POST">
            @csrf
            <input type="hidden" name="_method" id="method-type" value="POST">
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Nama Kelas</label>
                <input type="text" name="nama_kelas" id="nama_kelas" class="w-full border rounded-lg px-3 py-2 focus:ring-primary" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Jurusan</label>
                <select name="jurusan_id" id="jurusan_id" class="w-full border rounded-lg px-3 py-2 focus:ring-primary" required>
                    <option value="">-- Pilih Jurusan --</option>
                    @foreach($jurusan as $j)
                        <option value="{{ $j->id }}">{{ $j->nama_jurusan }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeModal('modal-form')" class="px-4 py-2 text-gray-500 bg-gray-100 rounded-lg">Batal</button>
                <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal(id) { 
        document.getElementById(id).classList.remove('hidden'); 
        document.getElementById('form-kelas').reset();
        document.getElementById('form-kelas').action = "{{ route('admin.kelas.store') }}";
        document.getElementById('method-type').value = "POST";
        document.getElementById('modal-title').innerText = "Tambah Kelas";
    }
    function closeModal(id) { document.getElementById(id).classList.add('hidden'); }
    
    function editData(id, nama, jurusan_id) {
        openModal('modal-form');
        document.getElementById('modal-title').innerText = "Edit Kelas";
        document.getElementById('form-kelas').action = `/admin/manajemen-siswa/kelas/${id}`; // Ganti ke URL Update
        document.getElementById('method-type').value = "PUT";
        
        document.getElementById('nama_kelas').value = nama;
        document.getElementById('jurusan_id').value = jurusan_id;
    }
</script>
@endsection
@extends('layouts.app')
@section('title', 'Master Tahun Ajaran')

@section('content')
<div class="bg-surface p-6 rounded-2xl shadow-sm">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-gray-800">Data Tahun Ajaran</h2>
        <button onclick="openModal('modal-form')" class="bg-primary hover:bg-primary_hover text-white px-4 py-2 rounded-lg text-sm">
            <i class="fa-solid fa-plus mr-2"></i> Tambah Tahun Ajaran
        </button>
    </div>

    <table class="w-full text-left border-collapse">
        <thead class="bg-gray-50 text-gray-500 text-sm">
            <tr>
                <th class="p-4 border-b">No</th>
                <th class="p-4 border-b">Tahun Ajaran</th>
                <th class="p-4 border-b">Status</th>
                <th class="p-4 border-b text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $key => $item)
            <tr class="border-b hover:bg-gray-50">
                <td class="p-4">{{ $key + 1 }}</td>
                <td class="p-4 font-semibold">{{ $item->tahun }}</td>
                <td class="p-4">
                    @if($item->is_active)
                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">Aktif</span>
                    @else
                        <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-xs font-semibold">Tidak Aktif</span>
                    @endif
                </td>
                <td class="p-4 flex justify-center gap-3">
                    <button onclick="editData({{ $item->id }}, '{{ $item->tahun }}', {{ $item->is_active }})" class="text-blue-500 hover:text-blue-700">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                    <form action="{{ route('admin.tahun.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin hapus data ini?')">
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
        <h3 id="modal-title" class="text-lg font-bold mb-4">Tambah Tahun Ajaran</h3>
        <form id="form-tahun" action="{{ route('admin.tahun.store') }}" method="POST">
            @csrf
            <input type="hidden" name="_method" id="method-type" value="POST">
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Tahun Ajaran</label>
                <input type="text" name="tahun" id="tahun" class="w-full border rounded-lg px-3 py-2 focus:ring-primary" placeholder="Contoh: 2024/2025" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Status Aktif</label>
                <select name="is_active" id="is_active" class="w-full border rounded-lg px-3 py-2 focus:ring-primary">
                    <option value="1">Aktif</option>
                    <option value="0">Tidak Aktif</option>
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
        document.getElementById('form-tahun').reset();
        document.getElementById('form-tahun').action = "{{ route('admin.tahun.store') }}";
        document.getElementById('method-type').value = "POST";
        document.getElementById('modal-title').innerText = "Tambah Tahun Ajaran";
    }
    function closeModal(id) { document.getElementById(id).classList.add('hidden'); }
    
    function editData(id, tahun, is_active) {
        openModal('modal-form');
        document.getElementById('modal-title').innerText = "Edit Tahun Ajaran";
        document.getElementById('form-tahun').action = `/admin/manajemen-siswa/tahun/${id}`;
        document.getElementById('method-type').value = "PUT";
        
        document.getElementById('tahun').value = tahun;
        document.getElementById('is_active').value = is_active;
    }
</script>
@endsection
@extends('layouts.app')
@section('title', 'Akun Login Siswa')

@section('content')
<div class="bg-surface p-4 md:p-6 rounded-2xl shadow-sm border border-gray-100">
    
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Akun Login Siswa</h2>
            <p class="text-sm text-gray-500 mt-1">Kelola akses email dan password untuk aplikasi siswa.</p>
        </div>
        <button onclick="openModalTambah()" class="w-full sm:w-auto bg-primary hover:bg-primary_hover text-white px-4 py-2.5 rounded-lg text-sm font-medium transition-colors shadow-sm flex items-center justify-center">
            <i class="fa-solid fa-user-plus mr-2"></i> Buat Akun Baru
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse min-w-max">
            <thead class="bg-gray-50 text-gray-500 text-sm border-y border-gray-100">
                <tr>
                    <th class="py-3 px-4 font-semibold w-16">No</th>
                    <th class="py-3 px-4 font-semibold">Nama Siswa</th>
                    <th class="py-3 px-4 font-semibold">Email Login</th>
                    <th class="py-3 px-4 font-semibold">Status Akses</th>
                    <th class="py-3 px-4 font-semibold text-center w-32">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-700">
                @forelse($akun as $key => $item)
                <tr class="border-b border-gray-50 hover:bg-gray-50 transition-colors">
                    <td class="py-3 px-4">{{ $key + 1 }}</td>
                    <td class="py-3 px-4 font-bold text-gray-900">{{ $item->name }}</td>
                    <td class="py-3 px-4 text-blue-600 font-medium">{{ $item->email }}</td>
                    <td class="py-3 px-4">
                        <span class="bg-green-100 text-green-700 px-2 py-1 rounded-md text-xs font-bold">Aktif</span>
                    </td>
                    <td class="py-3 px-4 flex justify-center gap-2">
                        <button onclick="openModalReset({{ $item->id }}, '{{ $item->name }}')" class="text-yellow-600 hover:text-yellow-800 bg-yellow-50 p-2 rounded-md transition-colors" title="Reset Password">
                            <i class="fa-solid fa-key"></i>
                        </button>
                        
                        <form action="{{ route('admin.akun.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus akses login untuk siswa ini? (Biodata siswa tidak akan terhapus)')">
                            @csrf 
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 bg-red-50 p-2 rounded-md transition-colors" title="Cabut Akses/Hapus">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-8 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fa-solid fa-users-slash text-4xl text-gray-300 mb-3"></i>
                            <p>Belum ada akun siswa yang dibuat.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div id="modal-tambah" class="fixed inset-0 z-[100] hidden bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity">
    <div class="bg-surface w-full max-w-md rounded-2xl shadow-xl overflow-hidden transform transition-all">
        <div class="flex justify-between items-center p-5 border-b border-gray-100 bg-gray-50/50">
            <h3 class="text-lg font-bold text-gray-800">Generate Akun Siswa</h3>
            <button onclick="closeModal('modal-tambah')" class="text-gray-400 hover:text-red-500"><i class="fa-solid fa-xmark text-xl"></i></button>
        </div>

        <form action="{{ route('admin.akun.store') }}" method="POST">
            @csrf
            <div class="p-5 space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Pilih Siswa <span class="text-red-500">*</span></label>
                    <select name="siswa_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-primary outline-none text-sm bg-white" required>
                        <option value="">-- Pilih Siswa (Belum Punya Akun) --</option>
                        @foreach($siswaBelumPunyaAkun as $s)
                            <option value="{{ $s->id }}">{{ $s->nama }} ({{ $s->kelas->nama_kelas ?? '-' }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Email Login <span class="text-red-500">*</span></label>
                    <input type="email" name="email" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-primary outline-none text-sm" placeholder="siswa@sekolah.com" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Password Default <span class="text-red-500">*</span></label>
                    <input type="text" name="password" value="12345678" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-primary outline-none text-sm bg-gray-50" required>
                    <p class="text-[10px] text-gray-400 mt-1">*Siswa dapat mengganti password ini nanti di aplikasi mereka.</p>
                </div>
            </div>

            <div class="flex justify-end gap-3 p-5 border-t border-gray-100 bg-gray-50/50">
                <button type="button" onclick="closeModal('modal-tambah')" class="px-4 py-2 text-sm text-gray-700 border rounded-lg hover:bg-gray-50">Batal</button>
                <button type="submit" class="px-5 py-2 text-sm text-white bg-primary hover:bg-primary_hover rounded-lg shadow-sm"><i class="fa-solid fa-floppy-disk mr-2"></i> Buat Akun</button>
            </div>
        </form>
    </div>
</div>

<div id="modal-reset" class="fixed inset-0 z-[100] hidden bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity">
    <div class="bg-surface w-full max-w-sm rounded-2xl shadow-xl overflow-hidden transform transition-all border-t-4 border-yellow-500">
        <div class="p-5 text-center border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-800">Reset Password</h3>
            <p class="text-sm text-gray-500 mt-1" id="reset-nama">Nama Siswa</p>
        </div>

        <form id="form-reset" action="" method="POST">
            @csrf
            @method('PUT')
            <div class="p-5">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Masukkan Password Baru</label>
                <input type="text" name="password" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-yellow-500 outline-none text-sm" placeholder="Minimal 6 karakter" required>
            </div>

            <div class="flex justify-end gap-3 p-5 border-t border-gray-100 bg-gray-50">
                <button type="button" onclick="closeModal('modal-reset')" class="px-4 py-2 text-sm text-gray-700 border rounded-lg hover:bg-gray-50">Batal</button>
                <button type="submit" class="px-5 py-2 text-sm text-white bg-yellow-500 hover:bg-yellow-600 rounded-lg shadow-sm">Reset</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModalTambah() { 
        document.getElementById('modal-tambah').classList.remove('hidden'); 
    }
    
    function openModalReset(id, nama) {
        document.getElementById('modal-reset').classList.remove('hidden');
        document.getElementById('reset-nama').innerText = nama;
        document.getElementById('form-reset').action = `/admin/manajemen-siswa/akun/${id}`;
    }

    function closeModal(id) { 
        document.getElementById(id).classList.add('hidden'); 
    }
</script>
@endsection
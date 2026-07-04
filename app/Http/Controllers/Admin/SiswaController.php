<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function index() {
        // Mengambil data siswa beserta relasi kelas (lengkap dengan jurusan) dan tahun ajaran
        $data = Siswa::with(['kelas.jurusan', 'tahunAjaran'])->latest()->get();
        
        // Mengambil master data untuk kebutuhan dropdown di Modal Form
        $kelas = Kelas::with('jurusan')->get(); 
        $tahun = TahunAjaran::all();
        
        return view('admin.manajemen-siswa.index', compact('data', 'kelas', 'tahun'));
    }

    public function store(Request $request) {
        $request->validate([
            'nisn' => 'required|unique:siswa', 
            'nama' => 'required', 
            'kelas_id' => 'required', 
            'tahun_ajaran_id' => 'required',
            'email_orang_tua' => 'required|email'
        ]);
        Siswa::create($request->all());
        return back()->with('success', 'Siswa berhasil ditambahkan!');
    }

    public function update(Request $request, string $id) {
        $request->validate([
            'nisn' => 'required|unique:siswa,nisn,'.$id, 
            'nama' => 'required',
            'kelas_id' => 'required',
            'tahun_ajaran_id' => 'required',
            'email_orang_tua' => 'required|email'
        ]);
        Siswa::findOrFail($id)->update($request->all());
        return back()->with('success', 'Siswa berhasil diupdate!');
    }

    public function destroy(string $id) {
        Siswa::findOrFail($id)->delete();
        return back()->with('success', 'Siswa berhasil dihapus!');
    }
}
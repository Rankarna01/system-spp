<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Jurusan;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index() {
        $data = Kelas::with('jurusan')->latest()->get();
        $jurusan = Jurusan::all();
        return view('admin.manajemen-siswa.master-kelas.index', compact('data', 'jurusan'));
    }

    public function store(Request $request) {
        $request->validate(['nama_kelas' => 'required', 'jurusan_id' => 'required']);
        Kelas::create($request->all());
        return back()->with('success', 'Kelas ditambahkan!');
    }

    public function update(Request $request, string $id) {
        Kelas::findOrFail($id)->update($request->all());
        return back()->with('success', 'Kelas diupdate!');
    }

    public function destroy(string $id) {
        Kelas::findOrFail($id)->delete();
        return back()->with('success', 'Kelas dihapus!');
    }
}
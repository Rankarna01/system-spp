<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Jurusan;
use Illuminate\Http\Request;

class JurusanController extends Controller
{
    public function index() {
        $data = Jurusan::latest()->get();
        return view('admin.manajemen-siswa.master-jurusan.index', compact('data'));
    }

    public function store(Request $request) {
        $request->validate(['nama_jurusan' => 'required']);
        Jurusan::create($request->all());
        return back()->with('success', 'Jurusan ditambahkan!');
    }

    public function update(Request $request, string $id) {
        $request->validate(['nama_jurusan' => 'required']);
        Jurusan::findOrFail($id)->update($request->all());
        return back()->with('success', 'Jurusan diupdate!');
    }

    public function destroy(string $id) {
        Jurusan::findOrFail($id)->delete();
        return back()->with('success', 'Jurusan dihapus!');
    }
}
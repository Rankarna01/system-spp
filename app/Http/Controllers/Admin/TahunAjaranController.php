<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class TahunAjaranController extends Controller
{
    public function index() {
        $data = TahunAjaran::latest()->get();
        return view('admin.manajemen-siswa.tahun-ajaran.index', compact('data'));
    }

    public function store(Request $request) {
        $request->validate(['tahun' => 'required']);
        TahunAjaran::create(['tahun' => $request->tahun, 'is_active' => $request->boolean('is_active')]);
        return back()->with('success', 'Tahun Ajaran ditambahkan!');
    }

    public function update(Request $request, string $id) {
        TahunAjaran::findOrFail($id)->update(['tahun' => $request->tahun, 'is_active' => $request->boolean('is_active')]);
        return back()->with('success', 'Tahun Ajaran diupdate!');
    }

    public function destroy(string $id) {
        TahunAjaran::findOrFail($id)->delete();
        return back()->with('success', 'Tahun Ajaran dihapus!');
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengaturan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengaturanController extends Controller
{
    public function index()
    {
        // Mengambil data pengaturan ID 1
        $pengaturan = Pengaturan::find(1);
        return view('admin.pengaturan.index', compact('pengaturan'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama_sistem' => 'required|string|max:50',
            'slogan_sistem' => 'required|string|max:50',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'telepon_sekolah' => 'nullable|string|max:20',
            'email_sekolah' => 'nullable|email|max:50',
            'alamat_sekolah' => 'nullable|string',
        ]);

        $pengaturan = Pengaturan::find(1);

        $data = [
            'nama_sistem' => $request->nama_sistem,
            'slogan_sistem' => $request->slogan_sistem,
            'telepon_sekolah' => $request->telepon_sekolah,
            'email_sekolah' => $request->email_sekolah,
            'alamat_sekolah' => $request->alamat_sekolah,
        ];

        // Logika Upload Logo Baru
        if ($request->hasFile('logo')) {
            // Hapus logo lama jika ada dan bukan bawaan default
            if ($pengaturan->logo && Storage::disk('public')->exists($pengaturan->logo)) {
                Storage::disk('public')->delete($pengaturan->logo);
            }

            // Simpan logo baru ke folder storage/app/public/logo
            $path = $request->file('logo')->store('logo', 'public');
            $data['logo'] = $path;
        }

        $pengaturan->update($data);

        return back()->with('success', 'Pengaturan sistem berhasil diperbarui!');
    }
}
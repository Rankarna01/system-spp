<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $table = 'siswa';
    protected $guarded = ['id'];

    public function kelas() {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
    public function tahunAjaran() {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }
}
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    protected $table = 'tahun_ajaran';
    protected $guarded = ['id'];

    public function siswa() {
        return $this->hasMany(Siswa::class, 'tahun_ajaran_id');
    }
}
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
    protected $table = 'jurusan'; // Override nama tabel
    protected $guarded = ['id'];

    public function kelas() {
        return $this->hasMany(Kelas::class, 'jurusan_id');
    }
}
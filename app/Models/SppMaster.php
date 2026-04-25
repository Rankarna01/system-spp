<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SppMaster extends Model {
    protected $table = 'spp_master';
    protected $guarded = ['id'];

    public function tahunAjaran() { return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id'); }
    public function tagihan() { return $this->hasMany(Tagihan::class, 'spp_master_id'); }
}
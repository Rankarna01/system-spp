<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    protected $table = 'tagihan';
    protected $guarded = ['id'];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }
    public function sppMaster()
    {
        return $this->belongsTo(SppMaster::class, 'spp_master_id');
    }

    public function pembayaran()
    {
        
        return $this->hasMany(Pembayaran::class, 'tagihan_id');
    }
    
    public function pembayaranAktif()
    {
        return $this->hasOne(Pembayaran::class, 'tagihan_id')->where('status', 'menunggu')->latest();
    }
}

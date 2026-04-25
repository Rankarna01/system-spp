<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model {
    protected $table = 'pembayaran';
    protected $guarded = ['id'];

    public function tagihan() { 
        return $this->belongsTo(Tagihan::class, 'tagihan_id'); 
    }
}
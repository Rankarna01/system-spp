<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model {
    protected $table = 'notifikasi';
    protected $guarded = ['id'];

    public function user() { 
        return $this->belongsTo(User::class, 'user_id'); 
    }
}
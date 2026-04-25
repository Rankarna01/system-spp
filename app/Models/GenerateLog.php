<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class GenerateLog extends Model {
    protected $table = 'generate_logs';
    public $timestamps = ["created_at"]; // Hanya butuh created_at
    const UPDATED_AT = null;
    protected $guarded = ['id'];

    public function kelas() { return $this->belongsTo(Kelas::class, 'kelas_id'); }
}
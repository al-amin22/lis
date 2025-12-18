<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JenisPemeriksaan extends Model
{
    use HasFactory;

    protected $table = 'jenis_pemeriksaan_1';
    protected $primaryKey = 'id_jenis_pemeriksaan_1';

    protected $fillable = [
        'nama_pemeriksaan',
    ];

    // Relasi ke pemeriksaan hematology
    public function dataPemeriksaan()
    {
        return $this->hasMany(DataPemeriksaan::class, 'id_jenis_pemeriksaan_1', 'id_jenis_pemeriksaan_1');
    }
}

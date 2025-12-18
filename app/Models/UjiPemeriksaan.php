<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UjiPemeriksaan extends Model
{
    use HasFactory;

    protected $table = 'uji_pemeriksaan';
    protected $primaryKey = 'id_uji_pemeriksaan';

    protected $fillable = [
        'no_lab',
        'kategori',
        'kode_pemeriksaan',
        'nama_pemeriksaan'
    ];

    /**
     * Relasi ke model Pasien
     */
    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'no_lab', 'no_lab');
    }
}

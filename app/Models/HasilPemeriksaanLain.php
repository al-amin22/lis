<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class HasilPemeriksaanLain extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'hasil_pemeriksaan_lain';
    protected $primaryKey = 'id_hasil_lain';

    protected $fillable = [
        'no_lab',
        'jenis_pengujian',
        'hasil_pengujian',
        'satuan_hasil_pengujian',
        'rujukan',
        'keterangan',
        'kode_pemeriksaan',
        'status_pemeriksaan',
        'id_jenis_pemeriksaan',
    ];

    // Relasi ke pasien
    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'no_lab', 'no_lab');
    }

    // Relasi ke jenis pemeriksaan
    public function jenisPemeriksaan()
    {
        return $this->belongsTo(JenisPemeriksaan::class, 'id_jenis_pemeriksaan', 'id_jenis_pemeriksaan');
    }

    public function dataPemeriksaan()
    {
        return $this->belongsTo(DataPemeriksaan::class, 'kode_pemeriksaan', 'kode_pemeriksaan');
    }
}

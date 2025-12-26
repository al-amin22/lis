<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class HasilPemeriksaanLain extends Model
{
    use HasFactory;

    protected $table = 'hasil_pemeriksaan_lain';
    protected $primaryKey = 'id_hasil_lain';

    protected $fillable = [
        'no_lab',
        'jenis_pengujian',
        'hasil_pengujian',
        'satuan_hasil_pengujian',
        'rujukan',
        'keterangan',
        'status_pemeriksaan',
        'id_jenis_pemeriksaan',
        'id_data_pemeriksaan',
        'created_at', // TAMBAHKAN
        'updated_at', // TAMBAHKAN
        'deleted_at'  // TAMBAHKAN
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
        return $this->belongsTo(DataPemeriksaan::class, 'id_data_pemeriksaan', 'id_data_pemeriksaan');
    }
}

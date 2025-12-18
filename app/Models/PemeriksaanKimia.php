<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class PemeriksaanKimia extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pemeriksaan_kimia';
    protected $primaryKey = 'id_pemeriksaan_kimia';

    protected $fillable = [
        'no_lab',
        'analysis',
        'method',
        'hasil_pengujian',
        'keterangan',
        'satuan_hasil_pengujian',
        'kode_pemeriksaan',
        'rujukan',
        'id_jenis_pemeriksaan',
        'tanggal_input',
        'catatan',
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

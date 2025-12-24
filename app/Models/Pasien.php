<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pasien extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'pasien';
    protected $primaryKey = 'no_lab';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'rm_pasien',
        'no_lab',
        'nota',
        'nama_pasien',
        'jenis_pemeriksaan',
        'email_pasien',
        'jenis_kelamin',
        'umur',
        'nomor_telepon',
        'tanggal_pemeriksaan',
        'jam_pemeriksaan',
        'dokter_spesialis',
        'keluhan',
        'tgl_pendaftaran',
        'tgl_lahir',
        'alamat',
        'id_ruangan',
        'id_kelas',
        'ket_klinik',
        'pengirim',
        'catatan',
        'id_dokter',
        'id_pemeriksa',
        'tgl_ambil_sample',
        'acc',
        'nomor_registrasi',
        'waktu_validasi',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'tgl_pendaftaran' => 'datetime',
    ];


    public function hematology()
    {
        return $this->hasMany(PemeriksaanHematology::class, 'no_lab', 'no_lab');
    }

    public function kimia()
    {
        return $this->hasMany(PemeriksaanKimia::class, 'no_lab', 'no_lab');
    }

    public function hasilPemeriksaanLain()
    {
        return $this->hasMany(HasilPemeriksaanLain::class, 'no_lab', 'no_lab');
    }

    public function dokter()
    {
        return $this->belongsTo(Dokter::class, 'id_dokter', 'id_dokter');
    }

    public function pemeriksa()
    {
        return $this->belongsTo(Pemeriksa::class, 'id_pemeriksa', 'id_pemeriksa');
    }

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'id_ruangan', 'id_ruangan');
    }

      public function ujiPemeriksaan()
    {
        return $this->hasMany(UjiPemeriksaan::class, 'no_lab', 'no_lab');
    }

    public function penjamin()
    {
        return $this->belongsTo(Penjamin::class, 'nama_penjamin', 'nota');
    }
}

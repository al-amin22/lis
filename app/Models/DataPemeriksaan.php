<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DataPemeriksaan extends Model
{
    use HasFactory;

    protected $table = 'data_pemeriksaan';
    protected $primaryKey = 'kode_pemeriksaan';
    public $incrementing = false; // Karena kode digenerate otomatis
    protected $keyType = 'string'; // Kode berupa string

    protected $fillable = [
        'kode_pemeriksaan',
        'data_pemeriksaan',
        'satuan',
        'rujukan',
        'id_jenis_pemeriksaan_1',
        'id_jenis_pemeriksaan_2',
        'metode',
    ];

    public function jenisPemeriksaan()
    {
        return $this->belongsTo(JenisPemeriksaan::class, 'id_jenis_pemeriksaan_1', 'id_jenis_pemeriksaan_1');
    }
    public function hasilPemeriksaan()
    {
        return $this->hasOne(HasilPemeriksaanLain::class, 'kode_pemeriksaan', 'kode_pemeriksaan');
    }

    public function hematology()
    {
        return $this->hasOne(PemeriksaanHematology::class, 'kode_pemeriksaan', 'kode_pemeriksaan');
    }

    public function kimia()
    {
        return $this->hasOne(PemeriksaanKimia::class, 'kode_pemeriksaan', 'kode_pemeriksaan');
    }

    public function lisMappings()
    {
        return $this->hasMany(LisMapping::class, 'kode_pemeriksaan', 'kode_pemeriksaan');
    }

    public function getAllLis()
    {
        return $this->lisMappings->pluck('lis')->toArray();
    }

    public function hasLisPartial($lis)
    {
        $searchLis = strtolower(trim($lis));
        return $this->lisMappings
            ->filter(function ($mapping) use ($searchLis) {
                return stripos(strtolower($mapping->lis), $searchLis) !== false;
            })
            ->isNotEmpty();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dokter extends Model
{
    protected $table = 'dokter';
    protected $primaryKey = 'id_dokter';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nama_dokter',
        'alamat',
        'no_telp',
    ];

    public function pasien()
    {
        return $this->hasMany(Pasien::class, 'id_dokter', 'id_dokter');
    }
}

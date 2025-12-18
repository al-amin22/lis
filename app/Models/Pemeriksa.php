<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemeriksa extends Model
{
    protected $table = 'pemeriksa';
    protected $primaryKey = 'id_pemeriksa';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nama_pemeriksa',
        'alamat',
        'no_telp',
    ];

    public function pasien()
    {
        return $this->hasMany(Pasien::class, 'id_pemeriksa', 'id_pemeriksa');
    }
}

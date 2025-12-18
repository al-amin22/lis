<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = 'kelas';
    protected $primaryKey = 'id_kelas';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nama_kelas',
    ];

    public function pasien()
    {
        return $this->hasMany(Pasien::class, 'id_kelas', 'id_kelas');
    }
}

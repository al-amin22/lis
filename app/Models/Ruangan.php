<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    protected $table = 'ruangan';
    protected $primaryKey = 'id_ruangan';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nama_ruangan',
    ];

    public function pasien()
    {
        return $this->hasMany(Pasien::class, 'id_ruangan', 'id_ruangan');
    }
}

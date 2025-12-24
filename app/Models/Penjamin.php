<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Penjamin extends Model
{
    protected $table = "penjamin";
    protected $primaryKey = 'id_penjamin';
    protected $fillable = [
        'nama_penjamin',
    ];

    public function pasien()
    {
        return $this->hasMany(Pasien::class, 'penjamin', 'nama_penjamin');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class LisMapping extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'lis_mapping';
    protected $primaryKey = 'id_lis_mapping';
    protected $fillable = [
        'lis',
        'kode_pemeriksaan',
    ];

    public function dataPemeriksaan()
    {
        return $this->belongsTo(DataPemeriksaan::class, 'kode_pemeriksaan', 'kode_pemeriksaan');
    }

    public function scopeSearchByLis($query, $lis)
    {
        return $query->where('lis', 'LIKE', '%' . $lis . '%');
    }
}

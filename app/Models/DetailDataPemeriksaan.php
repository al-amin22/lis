<?php
// app/Models/DetailDataPemeriksaan.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailDataPemeriksaan extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'detail_data_pemeriksaan';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_detail_data_pemeriksaan';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_data_pemeriksaan',
        'umur',
        'jenis_kelamin',
        'rujukan',
        'satuan',
        'metode',
        'ch',
        'cl',
        'urutan', // Perhatikan: di database Anda 'ururtan', di sini saya pakai 'urutan'
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'urutan' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * Get the data pemeriksaan that owns the detail.
     */
    public function dataPemeriksaan()
    {
        return $this->belongsTo(DataPemeriksaan::class, 'id_data_pemeriksaan', 'id_data_pemeriksaan');
    }

}

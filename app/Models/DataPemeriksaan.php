<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DataPemeriksaan extends Model
{
    use HasFactory;

    protected $table = 'data_pemeriksaan';
    protected $primaryKey = 'id_data_pemeriksaan';
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
        'urutan',
        'ch',
        'cl',
    ];


    public function jenisPemeriksaan()
    {
        return $this->belongsTo(JenisPemeriksaan::class, 'id_jenis_pemeriksaan_1', 'id_jenis_pemeriksaan_1');
    }
    public function hasilPemeriksaan()
    {
        return $this->hasOne(HasilPemeriksaanLain::class, 'id_data_pemeriksaan', 'id_data_pemeriksaan');
    }

    public function hematology()
    {
        return $this->hasOne(PemeriksaanHematology::class, 'id_data_pemeriksaan', 'id_data_pemeriksaan');
    }

    public function kimia()
    {
        return $this->hasOne(PemeriksaanKimia::class, 'id_data_pemeriksaan', 'id_data_pemeriksaan');
    }

    public function lisMappings()
    {
        return $this->hasMany(LisMapping::class, 'id_data_pemeriksaan', 'id_data_pemeriksaan');
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

    public function detailConditions()
    {
        return $this->hasMany(DetailDataPemeriksaan::class, 'id_data_pemeriksaan', 'id_data_pemeriksaan')
            ->whereNull('deleted_at')
            ->orderBy('urutan', 'asc');
    }

    /**
     * Mendapatkan rujukan berdasarkan kondisi pasien
     */
    public function getRujukanByKondisiPasien($jenisKelamin, $umurPasien)
    {
        // Convert umur ke format hari
        $umurHari = $this->parseUmurToHari($umurPasien);

        // Ambil semua detail kondisi
        $details = $this->detailConditions;

        // Default rujukan dari tabel utama
        $defaultRujukan = [
            'rujukan' => $this->rujukan,
            'satuan' => $this->satuan,
            'ch' => $this->ch,
            'cl' => $this->cl,
            'metode' => $this->metode,
            'is_from_detail' => false,
            'detail_condition' => null
        ];

        if ($details->isEmpty()) {
            return $defaultRujukan;
        }

        // Prioritas 1: Cek kondisi lengkap (jenis kelamin + umur)
        foreach ($details as $detail) {
            $isJenisKelaminMatch = $this->isJenisKelaminMatch($detail->jenis_kelamin, $jenisKelamin);
            $isUmurMatch = $this->isUmurMatch($detail->umur, $umurHari);

            if ($isJenisKelaminMatch && $isUmurMatch) {
                return $this->getDetailRujukan($detail, true);
            }
        }

        // Prioritas 2: Cek hanya jenis kelamin (umur kosong)
        foreach ($details as $detail) {
            $isJenisKelaminMatch = $this->isJenisKelaminMatch($detail->jenis_kelamin, $jenisKelamin);
            $isUmurEmpty = empty($detail->umur) || $detail->umur === '-' || $detail->umur === '';

            if ($isJenisKelaminMatch && $isUmurEmpty) {
                return $this->getDetailRujukan($detail, true);
            }
        }

        // Prioritas 3: Cek hanya umur (jenis kelamin kosong)
        foreach ($details as $detail) {
            $isUmurMatch = $this->isUmurMatch($detail->umur, $umurHari);
            $isJenisKelaminEmpty = empty($detail->jenis_kelamin) || $detail->jenis_kelamin === '-' || $detail->jenis_kelamin === '';

            if ($isUmurMatch && $isJenisKelaminEmpty) {
                return $this->getDetailRujukan($detail, true);
            }
        }

        // Prioritas 4: Return default
        return $defaultRujukan;
    }

    private function getDetailRujukan($detail, $isFromDetail)
    {
        return [
            'rujukan' => $detail->rujukan ?? $this->rujukan,
            'satuan' => $detail->satuan ?? $this->satuan,
            'ch' => $detail->ch ?? $this->ch,
            'cl' => $detail->cl ?? $this->cl,
            'metode' => $detail->metode ?? $this->metode,
            'is_from_detail' => $isFromDetail,
            'detail_condition' => [
                'id' => $detail->id_detail_data_pemeriksaan,
                'urutan' => $detail->urutan,
                'jenis_kelamin' => $detail->jenis_kelamin,
                'umur' => $detail->umur
            ]
        ];
    }

    public function parseUmurToHari($umur)
    {
        if (is_array($umur)) {
            $tahun = $umur['tahun'] ?? 0;
            $bulan = $umur['bulan'] ?? 0;
            $hari = $umur['hari'] ?? 0;
        } else {
            preg_match('/(\d+)\s*Th.*?(\d+)\s*Bln.*?(\d+)\s*Hari/', $umur, $matches);

            if (count($matches) === 4) {
                $tahun = (int)$matches[1];
                $bulan = (int)$matches[2];
                $hari = (int)$matches[3];
            } else {
                $tahun = 0; $bulan = 0; $hari = 0;

                if (preg_match('/(\d+)\s*tahun/i', $umur, $tMatches)) {
                    $tahun = (int)$tMatches[1];
                }
                if (preg_match('/(\d+)\s*bulan/i', $umur, $bMatches)) {
                    $bulan = (int)$bMatches[1];
                }
                if (preg_match('/(\d+)\s*hari/i', $umur, $hMatches)) {
                    $hari = (int)$hMatches[1];
                }
            }
        }

        return ($tahun * 365) + ($bulan * 30) + $hari;
    }

    private function isJenisKelaminMatch($detailJenisKelamin, $pasienJenisKelamin)
    {
        if (empty($detailJenisKelamin) || $detailJenisKelamin === '-' || $detailJenisKelamin === '') {
            return true;
        }

        $detailJK = strtoupper(trim($detailJenisKelamin));
        $pasienJK = strtoupper(trim($pasienJenisKelamin));

        $mapping = [
            'PRIA' => ['PRIA', 'LAKI-LAKI', 'LAKI', 'L', 'MALE'],
            'WANITA' => ['WANITA', 'PEREMPUAN', 'PEREMPUAN', 'P', 'FEMALE']
        ];

        foreach ($mapping as $key => $values) {
            if (in_array($detailJK, $values) && in_array($pasienJK, $values)) {
                return true;
            }
        }

        return false;
    }

    private function isUmurMatch($kondisiUmur, $umurHari)
    {
        if (empty($kondisiUmur) || $kondisiUmur === '-' || $kondisiUmur === '') {
            return true;
        }

        $kondisi = trim($kondisiUmur);

        // Format: "1 - 2 th"
        if (preg_match('/^(\d+(?:\.\d+)?)\s*-\s*(\d+(?:\.\d+)?)\s*(tahun|th|bulan|bln|hari)$/i', $kondisi, $matches)) {
            $min = (float)$matches[1];
            $max = (float)$matches[2];
            $unit = strtolower($matches[3]);

            $minHari = $this->convertToHari($min, $unit);
            $maxHari = $this->convertToHari($max, $unit);

            return $umurHari >= $minHari && $umurHari <= $maxHari;
        }

        // Format: "<= 4 hari"
        if (preg_match('/^<=\s*(\d+(?:\.\d+)?)\s*(tahun|th|bulan|bln|hari)$/i', $kondisi, $matches)) {
            $batas = (float)$matches[1];
            $unit = strtolower($matches[2]);
            $batasHari = $this->convertToHari($batas, $unit);

            return $umurHari <= $batasHari;
        }

        // Format: ">= 5 bulan"
        if (preg_match('/^>=\s*(\d+(?:\.\d+)?)\s*(tahun|th|bulan|bln|hari)$/i', $kondisi, $matches)) {
            $batas = (float)$matches[1];
            $unit = strtolower($matches[2]);
            $batasHari = $this->convertToHari($batas, $unit);

            return $umurHari >= $batasHari;
        }

        // Format: "< 1 tahun"
        if (preg_match('/^<\s*(\d+(?:\.\d+)?)\s*(tahun|th|bulan|bln|hari)$/i', $kondisi, $matches)) {
            $batas = (float)$matches[1];
            $unit = strtolower($matches[2]);
            $batasHari = $this->convertToHari($batas, $unit);

            return $umurHari < $batasHari;
        }

        // Format: "> 18 tahun"
        if (preg_match('/^>\s*(\d+(?:\.\d+)?)\s*(tahun|th|bulan|bln|hari)$/i', $kondisi, $matches)) {
            $batas = (float)$matches[1];
            $unit = strtolower($matches[2]);
            $batasHari = $this->convertToHari($batas, $unit);

            return $umurHari > $batasHari;
        }

        return false;
    }

    private function convertToHari($nilai, $unit)
    {
        switch ($unit) {
            case 'tahun':
            case 'th':
                return (int)($nilai * 365);
            case 'bulan':
            case 'bln':
                return (int)($nilai * 30);
            case 'hari':
                return (int)$nilai;
            default:
                return 0;
        }
    }

    /**
     * Cek apakah ada kondisi detail untuk pemeriksaan ini
     */
    public function hasDetailConditions()
    {
        return $this->detailConditions()->exists();
    }

    /**
     * Mendapatkan semua detail kondisi untuk debugging
     */
    public function getAllRujukanDetails()
    {
        $details = $this->detailConditions()->get();

        return [
            'main' => [
                'rujukan' => $this->rujukan,
                'satuan' => $this->satuan,
                'ch' => $this->ch,
                'cl' => $this->cl,
                'metode' => $this->metode
            ],
            'details' => $details->map(function($detail) {
                return [
                    'id' => $detail->id_detail_data_pemeriksaan,
                    'urutan' => $detail->urutan,
                    'jenis_kelamin' => $detail->jenis_kelamin,
                    'umur' => $detail->umur,
                    'rujukan' => $detail->rujukan,
                    'satuan' => $detail->satuan,
                    'ch' => $detail->ch,
                    'cl' => $detail->cl,
                    'metode' => $detail->metode
                ];
            })->toArray()
        ];
    }
}

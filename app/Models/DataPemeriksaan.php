<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DataPemeriksaan extends Model
{
    use HasFactory;

    protected $table = 'data_pemeriksaan';
    protected $primaryKey = 'id_data_pemeriksaan';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_data_pemeriksaan',
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
        'kode_uji_pemeriksaan',
    ];

    public function jenis()
    {
        return $this->belongsTo(
            JenisPemeriksaan::class,
            'id_jenis_pemeriksaan_1',
            'id_jenis_pemeriksaan_1'
        );
    }


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
        // sebelumnya hasOne — ubah ke hasMany supaya satu data pemeriksaan bisa memiliki banyak baris hematology (per pasien)
        return $this->hasMany(PemeriksaanHematology::class, 'id_data_pemeriksaan', 'id_data_pemeriksaan');
    }

    public function kimia()
    {
        return $this->hasOne(PemeriksaanKimia::class, 'id_data_pemeriksaan', 'id_data_pemeriksaan');
    }


    public function getAllLis()
    {
        return $this->lisMappings->pluck('lis')->toArray();
    }

    public function detailConditions()
    {
        return $this->hasMany(DetailDataPemeriksaan::class, 'id_data_pemeriksaan', 'id_data_pemeriksaan')
            ->whereNull('deleted_at')
            ->orderBy('urutan', 'asc');
    }

    public function lisMappings()
    {
        return $this->hasMany(LisMapping::class, 'id_data_pemeriksaan', 'id_data_pemeriksaan');
    }

    public function hasDetailConditions(): bool
    {
        return $this->detailConditions()->exists();
    }

        // ----------------------- RUJUKAN -----------------------
    public function getRujukanByKondisiPasien($jenisKelamin, $umurPasien): array
    {
        $umurHari = self::normalizeUmurToHari($umurPasien);

        $defaultRujukan = [
            'rujukan' => $this->rujukan,
            'satuan' => $this->satuan,
            'ch' => $this->ch,
            'cl' => $this->cl,
            'metode' => $this->metode,
            'is_from_detail' => false,
            'detail_condition' => null,
        ];

        $details = $this->detailConditions()->get();
        if ($details->isEmpty()) {
            return $defaultRujukan;
        }

        // Urutkan agar range yang paling sempit diproses dulu (opsional tetapi membantu)
        $details = $details->sortBy(function ($d) {
            if (preg_match('/([\d\.]+)\s*-\s*([\d\.]+)\s*(tahun|thn|th|bulan|bln|hari)/i', $d->umur ?? '', $m)) {
                return ((float)$m[2] - (float)$m[1]);
            }
            return PHP_INT_MAX;
        })->values();

        // 1) Jika umur pasien tidak diketahui/null -> cari rule yang hanya berdasarkan gender (umur kosong/'-')
        if ($umurHari === null) {
            foreach ($details as $detail) {
                if ($this->isJenisKelaminMatch($detail->jenis_kelamin, $jenisKelamin)
                    && (empty(trim($detail->umur ?? '')) || trim($detail->umur) === '-')) {
                    return $this->getDetailRujukan($detail, true);
                }
            }
            return $defaultRujukan;
        }

        // 2) Prioritas: jenis kelamin cocok AND umur cocok
        foreach ($details as $detail) {
            if ($this->isJenisKelaminMatch($detail->jenis_kelamin, $jenisKelamin)
                && $this->isUmurMatch($detail->umur, $umurHari)) {
                return $this->getDetailRujukan($detail, true);
            }
        }

        // 3) Jenis kelamin cocok, umur kosong/- (prioritaskan detail yang memang tidak punya aturan umur)
        foreach ($details as $detail) {
            if ($this->isJenisKelaminMatch($detail->jenis_kelamin, $jenisKelamin)
                && (empty(trim($detail->umur ?? '')) || trim($detail->umur) === '-')) {
                return $this->getDetailRujukan($detail, true);
            }
        }

        // 4) FALLBACK: Jenis kelamin cocok — ambil meskipun detail punya umur yang tidak cocok
        foreach ($details as $detail) {
            if ($this->isJenisKelaminMatch($detail->jenis_kelamin, $jenisKelamin)) {
                // Di sini kita sengaja abaikan pengecekan umur
                return $this->getDetailRujukan($detail, true);
            }
        }

        // 5) Umur cocok, jenis kelamin kosong/- pada detail
        foreach ($details as $detail) {
            if ((empty(trim($detail->jenis_kelamin ?? '')) || trim($detail->jenis_kelamin) === '-')
                && $this->isUmurMatch($detail->umur, $umurHari)) {
                return $this->getDetailRujukan($detail, true);
            }
        }


        // 5) Jika tidak ada yang match kembalikan default
        return $defaultRujukan;
    }

    private function getDetailRujukan($detail, bool $isFromDetail): array
    {
        return [
            'rujukan' => $detail->rujukan ?? $this->rujukan,
            'satuan' => $detail->satuan ?? $this->satuan,
            'ch' => $detail->ch ?? $this->ch,
            'cl' => $detail->cl ?? $this->cl,
            'metode' => $detail->metode ?? $this->metode,
            'is_from_detail' => $isFromDetail,
            'detail_condition' => [
                'id' => $detail->id_detail_data_pemeriksaan ?? null,
                'urutan' => $detail->urutan ?? null,
                'jenis_kelamin' => $detail->jenis_kelamin ?? null,
                'umur' => $detail->umur ?? null,
            ],
        ];
    }

    // ----------------------- UMUR PARSER -----------------------
    public static function normalizeUmurToHari($umur): ?int
    {
        if ($umur === null || $umur === '') return null;

        if (is_int($umur)) return $umur;

        if (is_array($umur)) {
            $tahun = isset($umur['tahun']) ? (int)$umur['tahun'] : 0;
            $bulan = isset($umur['bulan']) ? (int)$umur['bulan'] : 0;
            $hari  = isset($umur['hari'])  ? (int)$umur['hari']  : 0;
            return ($tahun * 365) + ($bulan * 30) + $hari;
        }

        if (is_string($umur)) {
            $s = trim(str_replace(',', '.', $umur));
            if (is_numeric($s)) return (int) round((float) $s);
            return self::parseUmurToHari($s);
        }

        return null;
    }

    protected static function parseUmurToHari(string $umur): ?int
    {
        $tahun = 0.0; $bulan = 0.0; $hari = 0.0;
        $s = trim(preg_replace('/\s+/', ' ', str_replace(',', '.', $umur)));

        if (preg_match_all('/([\d\.]+)\s*(tahun|thn|th|th\.|bulan|bln|bln\.|hari|hr)/i', $s, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $m) {
                $val = (float) $m[1];
                $u = strtolower($m[2]);
                if (preg_match('/^(tahun|thn|th|th\.)$/i', $u)) $tahun = $val;
                elseif (preg_match('/^(bulan|bln|bln\.)$/i', $u)) $bulan = $val;
                elseif (preg_match('/^(hari|hr)$/i', $u)) $hari = $val;
            }

            $total = ($tahun * 365.0) + ($bulan * 30.0) + $hari;
            return (int) round($total);
        }

        if (preg_match('/^(\d+)\D+(\d+)\D+(\d+)$/', $s, $m2)) {
            return ((int)$m2[1]) * 365 + ((int)$m2[2]) * 30 + ((int)$m2[3]);
        }

        return null;
    }

    // ----------------------- HELPERS MATCH -----------------------
    private function isJenisKelaminMatch($detailJenisKelamin, $pasienJenisKelamin): bool
    {
        if (empty($detailJenisKelamin) || trim($detailJenisKelamin) === '-') return true;

        $detailJK = strtoupper(trim($detailJenisKelamin));
        $pasienJK = strtoupper(trim($pasienJenisKelamin ?? ''));

        $mapping = [
            'PRIA' => ['PRIA', 'LAKI-LAKI', 'LAKI', 'L', 'MALE'],
            'WANITA' => ['WANITA', 'PEREMPUAN', 'P', 'FEMALE']
        ];

        foreach ($mapping as $values) {
            if (in_array($detailJK, $values, true) && in_array($pasienJK, $values, true)) return true;
        }

        return false;
    }

    private function isUmurMatch($kondisiUmur, ?int $umurHari): bool
    {
        if ($umurHari === null) return false;
        if (empty($kondisiUmur) || trim($kondisiUmur) === '-') return true;

        $kondisi = trim(str_replace(',', '.', $kondisiUmur));

        if (preg_match('/^\s*([\d\.]+)\s*-\s*([\d\.]+)\s*(tahun|thn|th|bulan|bln|hari)\s*$/i', $kondisi, $m)) {
            $min = (float)$m[1]; $max = (float)$m[2]; $unit = strtolower($m[3]);
            $minHari = $this->convertToHari($min, $unit);
            $maxHari = $this->convertToHari($max, $unit);
            return ($umurHari >= $minHari) && ($umurHari <= $maxHari);
        }

        if (preg_match('/^\s*<=\s*([\d\.]+)\s*(tahun|thn|th|bulan|bln|hari)\s*$/i', $kondisi, $m)) {
            return $umurHari <= $this->convertToHari((float)$m[1], strtolower($m[2]));
        }
        if (preg_match('/^\s*>=\s*([\d\.]+)\s*(tahun|thn|th|bulan|bln|hari)\s*$/i', $kondisi, $m)) {
            return $umurHari >= $this->convertToHari((float)$m[1], strtolower($m[2]));
        }
        if (preg_match('/^\s*<\s*([\d\.]+)\s*(tahun|thn|th|bulan|bln|hari)\s*$/i', $kondisi, $m)) {
            return $umurHari < $this->convertToHari((float)$m[1], strtolower($m[2]));
        }
        if (preg_match('/^\s*>\s*([\d\.]+)\s*(tahun|thn|th|bulan|bln|hari)\s*$/i', $kondisi, $m)) {
            return $umurHari > $this->convertToHari((float)$m[1], strtolower($m[2]));
        }

        if (preg_match('/^\s*([\d\.]+)\s*(tahun|thn|th|bulan|bln|hari)\s*$/i', $kondisi, $m)) {
            return $umurHari === $this->convertToHari((float)$m[1], strtolower($m[2]));
        }

        return false;
    }

    private function convertToHari(float $nilai, string $unit): int
    {
        $u = strtolower(trim($unit));
        switch ($u) {
            case 'tahun': case 'th': case 'thn': return (int) round($nilai * 365.0);
            case 'bulan': case 'bln': return (int) round($nilai * 30.0);
            case 'hari': return (int) round($nilai);
            default: return 0;
        }
    }
}





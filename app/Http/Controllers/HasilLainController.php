<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HasilPemeriksaanLain;
use App\Models\Pasien;
use App\Models\DataPemeriksaan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Services\LogActivityService;

class HasilLainController extends Controller
{

    // Method untuk mengambil data pemeriksaan berdasarkan jenis
    // HasilLabController.php

    public function getPemeriksaanByJenis(Request $request)
    {
        $request->validate([
            'jenis_pemeriksaan' => 'required|string'
        ]);

        $jenisPemeriksaan = $request->jenis_pemeriksaan;

        // Cari jenis pemeriksaan berdasarkan nama
        $jenis = DB::table('jenis_pemeriksaan_1')
            ->where('nama_pemeriksaan', $jenisPemeriksaan)
            ->whereNull('deleted_at')
            ->first();

        if (!$jenis) {
            return response()->json([
                'success' => false,
                'message' => 'Jenis pemeriksaan tidak ditemukan'
            ], 404);
        }

        // Ambil semua data pemeriksaan untuk jenis ini
        $dataPemeriksaan = DB::table('data_pemeriksaan as dp')
            ->where('dp.id_jenis_pemeriksaan_1', $jenis->id_jenis_pemeriksaan_1)
            ->whereNull('dp.deleted_at')
            ->select(
                'dp.id_data_pemeriksaan',
                'dp.kode_pemeriksaan',
                'dp.data_pemeriksaan',
                'dp.satuan',
                'dp.rujukan',
                'dp.ch',
                'dp.cl',
                'dp.metode'
            )
            ->orderByRaw("
                    CASE
                        WHEN trim(dp.kode_pemeriksaan) ~ '^[0-9]+$' THEN 0
                        ELSE 1
                    END,
                    CASE
                        WHEN trim(dp.kode_pemeriksaan) ~ '^[0-9]+$'
                        THEN trim(dp.kode_pemeriksaan)::integer
                        ELSE NULL
                    END,
                    dp.kode_pemeriksaan
                ")
            ->get();

        LogActivityService::log(
            action: 'READ',
            module: 'Hasil Pemeriksaan Lain',
            description: 'User mengambil data pemeriksaan untuk jenis: ' . $jenisPemeriksaan
        );

        return response()->json([
            'success' => true,
            'data' => $dataPemeriksaan
        ]);
    }

    public function searchKodePemeriksaan(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'search' => 'nullable|string|max:100',
                'jenis_pemeriksaan' => 'nullable|string|max:100',
                'exclude_current' => 'nullable|string|max:50'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $search = $request->input('search', '');
            $jenisPemeriksaan = $request->input('jenis_pemeriksaan', '');
            $excludeCurrent = $request->input('exclude_current', '');

            // Query dengan debugging
            $query = DB::table('data_pemeriksaan as dp')
                ->select(
                    'dp.id_data_pemeriksaan',
                    'dp.data_pemeriksaan',
                    'dp.satuan',
                    'dp.rujukan',
                    'dp.metode',
                    'jp1.nama_pemeriksaan as jenis_pemeriksaan'
                );

            // Join dengan jenis_pemeriksaan_1
            $query->leftJoin('jenis_pemeriksaan_1 as jp1', 'dp.id_jenis_pemeriksaan_1', '=', 'jp1.id_jenis_pemeriksaan_1');

            // Kondisi where
            $query->whereNull('dp.deleted_at')
                ->whereNull('jp1.deleted_at');

            // Filter by jenis pemeriksaan jika ada
            if (!empty($jenisPemeriksaan) && $jenisPemeriksaan !== '') {
                $query->where('jp1.nama_pemeriksaan', '=', $jenisPemeriksaan);
            }

            // Exclude current kode jika ada
            if (!empty($excludeCurrent) && $excludeCurrent !== '') {
                $query->where('dp.id_data_pemeriksaan', '!=', $excludeCurrent);
            }

            // Search term jika ada
            if (!empty($search) && strlen($search) >= 2) {
                $searchTerm = '%' . $search . '%';
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('dp.id_data_pemeriksaan', 'ILIKE', $searchTerm)
                        ->orWhere('dp.data_pemeriksaan', 'ILIKE', $searchTerm)
                        ->orWhere('jp1.nama_pemeriksaan', 'ILIKE', $searchTerm);
                });
            }

            // Order dan limit
            $query->orderBy('dp.data_pemeriksaan')
                ->limit(20);

            // Debug query SQL
            $sql = $query->toSql();
            $bindings = $query->getBindings();

            $results = $query->get();

            LogActivityService::log(
                action: 'READ',
                module: 'Hasil Pemeriksaan Lain',
                description: 'User melakukan pencarian kode pemeriksaan dengan istilah: ' . $search
            );

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil ditemukan',
                'data' => $results,
                'debug' => [
                    'sql' => $sql,
                    'bindings' => $bindings,
                    'search_term' => $search
                ]
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'error_details' => [
                    'message' => $e->getMessage(),
                    'code' => $e->getCode(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            ], 500);
        }
    }

    public function store(Request $request)
    {
        Log::info('HasilLainController store called', $request->all());

        $validator = Validator::make($request->all(), [
            'no_lab' => 'required|exists:pasien,no_lab',
            'id_data_pemeriksaan' => 'required|exists:data_pemeriksaan,id_data_pemeriksaan',
            'jenis_pengujian' => 'required|string|max:100',
            'hasil_pengujian' => 'nullable|string|max:100',
            'satuan' => 'nullable|string|max:50',
            'rujukan' => 'nullable|string|max:100'
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed', $validator->errors()->toArray());

            LogActivityService::log(
                action: 'VALIDATION_FAILED',
                module: 'Hasil Pemeriksaan Lain',
                description: 'Validasi gagal saat simpan hasil pemeriksaan lain',
            );

            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $existing = HasilPemeriksaanLain::withTrashed()
                ->where('no_lab', $request->no_lab)
                ->where('jenis_pengujian', $request->jenis_pengujian)
                ->first();

            $dataPemeriksaan = DataPemeriksaan::where('id_data_pemeriksaan', $request->id_data_pemeriksaan)
                ->whereNull('deleted_at')
                ->firstOrFail();

            $keterangan = $this->determineKeterangan(
                $request->hasil_pengujian,
                $dataPemeriksaan->rujukan
            );

            // RESTORE + UPDATE
            if ($existing && $existing->deleted_at !== null) {
                $oldData = $existing->toArray();

                $existing->restore();
                $existing->update([
                    'id_data_pemeriksaan' => $request->id_data_pemeriksaan,
                    'jenis_pengujian' => $request->jenis_pengujian,
                    'hasil_pengujian' => $request->hasil_pengujian,
                    'satuan_hasil_pengujian' => $request->satuan ?? $dataPemeriksaan->satuan,
                    'rujukan' => $request->rujukan ?? $dataPemeriksaan->rujukan,
                    'keterangan' => $keterangan,
                    'updated_at' => now()->timezone('Asia/Jakarta')
                ]);

                Pasien::where('no_lab', $request->no_lab)
                    ->update(['updated_at' => now()->timezone('Asia/Jakarta')]);

                LogActivityService::log(
                    action: 'RESTORE_UPDATE',
                    module: 'Hasil Pemeriksaan Lain',
                    description: 'Restore dan update hasil pemeriksaan lain dengan no_lab: ' . $request->no_lab,
                    oldData: $oldData,
                    newData: $existing->toArray()

                );

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Pemeriksaan berhasil direstore dan diperbarui',
                    'data' => $existing
                ]);
            }

            // UPDATE
            if ($existing && $existing->deleted_at === null) {
                $oldData = $existing->toArray();

                $existing->update([
                    'id_data_pemeriksaan' => $request->id_data_pemeriksaan,
                    'jenis_pengujian' => $request->jenis_pengujian,
                    'hasil_pengujian' => $request->hasil_pengujian,
                    'satuan_hasil_pengujian' => $request->satuan ?? $dataPemeriksaan->satuan,
                    'rujukan' => $request->rujukan ?? $dataPemeriksaan->rujukan,
                    'keterangan' => $keterangan,
                    'updated_at' => now()->timezone('Asia/Jakarta')
                ]);

                Pasien::where('no_lab', $request->no_lab)
                    ->update(['updated_at' => now()->timezone('Asia/Jakarta')]);

                LogActivityService::log(
                    action: 'UPDATE',
                    module: 'Hasil Pemeriksaan Lain',
                    description: 'Update hasil pemeriksaan lain dengan no_lab: ' . $request->no_lab,
                    oldData: $oldData,
                    newData: $existing->toArray()
                );

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Pemeriksaan berhasil diperbarui',
                    'data' => $existing
                ]);
            }

            // INSERT
            $hasilLain = HasilPemeriksaanLain::create([
                'no_lab' => $request->no_lab,
                'jenis_pengujian' => $request->jenis_pengujian,
                'id_data_pemeriksaan' => $request->id_data_pemeriksaan,
                'hasil_pengujian' => $request->hasil_pengujian,
                'satuan_hasil_pengujian' => $request->satuan ?? $dataPemeriksaan->satuan,
                'rujukan' => $request->rujukan ?? $dataPemeriksaan->rujukan,
                'keterangan' => $keterangan,
                'status_pemeriksaan' => 'selesai'
            ]);

            Pasien::where('no_lab', $request->no_lab)
                ->update(['updated_at' => now()->timezone('Asia/Jakarta')]);

            LogActivityService::log(
                action: 'CREATE',
                module: 'Hasil Pemeriksaan Lain',
                description: 'Tambah hasil pemeriksaan lain dengan no_lab: ' . $request->no_lab,
                newData: $hasilLain->toArray()
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pemeriksaan berhasil ditambahkan',
                'data' => $hasilLain
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            LogActivityService::log(
                action: 'ERROR',
                module: 'Hasil Pemeriksaan Lain',
                description: 'Gagal simpan hasil pemeriksaan lain',
            );

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateKodePemeriksaan(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'id_data_pemeriksaan' => 'required|exists:data_pemeriksaan,id_data_pemeriksaan',
            'jenis_pengujian' => 'nullable|string|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $hasilLain = HasilPemeriksaanLain::findOrFail($id);

            // Ambil data dari data_pemeriksaan
            $dataPemeriksaan = DataPemeriksaan::where('id_data_pemeriksaan', $request->id_data_pemeriksaan)
                ->whereNull('deleted_at')
                ->first();

            $oldData = $hasilLain->toArray();

            if (!$dataPemeriksaan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data pemeriksaan tidak ditemukan'
                ], 404);
            }

            // Update record
            $hasilLain->update([
                'id_data_pemeriksaan' => $request->id_data_pemeriksaan,
                'jenis_pengujian' => $request->jenis_pengujian ?? $dataPemeriksaan->data_pemeriksaan,
                'satuan_hasil_pengujian' => $dataPemeriksaan->satuan,
                'rujukan' => $dataPemeriksaan->rujukan
            ]);

            LogActivityService::log(
                action: 'UPDATE',
                module: 'Hasil Pemeriksaan Lain',
                description: 'Mengupdate kode pemeriksaan hasil lain dengan ID: ' . $id,
                oldData: $oldData,
                newData: $hasilLain->toArray()
            );

            // Hitung ulang keterangan jika ada hasil
            if ($hasilLain->hasil_pengujian) {
                $hasilLain->keterangan = $this->determineKeterangan(
                    $hasilLain->hasil_pengujian,
                    $dataPemeriksaan->rujukan
                );
                $hasilLain->save();
            }

            // Update timestamp pasien
            Pasien::where('no_lab', $hasilLain->no_lab)->update(['updated_at' => now()]);

            DB::commit();

            LogActivityService::log(
                action: 'UPDATE',
                module: 'Hasil Pemeriksaan Lain',
                description: 'Update kode pemeriksaan hasil lain dengan ID: ' . $id,
                oldData: $oldData,
                newData: $hasilLain->toArray()
            );

            return response()->json([
                'success' => true,
                'message' => 'Kode pemeriksaan berhasil diperbarui',
                'data' => [
                    'id_hasil_lain' => $hasilLain->id_hasil_lain,
                    'jenis_pengujian' => $hasilLain->jenis_pengujian,
                    'id_data_pemeriksaan' => $hasilLain->id_data_pemeriksaan,
                    'satuan' => $hasilLain->satuan_hasil_pengujian,
                    'rujukan' => $hasilLain->rujukan,
                    'keterangan' => $hasilLain->keterangan
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $hasilLain = HasilPemeriksaanLain::where('id_hasil_lain', $id)->firstOrFail();
            $no_lab = $hasilLain->no_lab;

            $oldData = $hasilLain->toArray();

            // HARD DELETE (benar-benar hapus dari DB)
            $hasilLain->forceDelete();

            // Update timestamp pasien
            Pasien::where('no_lab', $no_lab)->update(['updated_at' => now()]);

            DB::commit();

            LogActivityService::log(
                action: 'DELETE',
                module: 'Hasil Pemeriksaan Lain',
                description: 'Force delete hasil pemeriksaan lain ID: ' . $id,
                oldData: $oldData
            );

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus permanen'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroyMultiple(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids'   => 'required|array',
            'ids.*' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            // Ambil data lama untuk log
            $data = HasilPemeriksaanLain::whereIn('id_hasil_lain', $request->ids)->get();

            if ($data->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data sudah tidak ada',
                    'deleted_count' => 0
                ]);
            }

            $oldData = $data->toArray();

            // 🔥 FORCE DELETE (HARD DELETE)
            $deletedCount = HasilPemeriksaanLain::whereIn('id_hasil_lain', $request->ids)
                ->forceDelete();

            DB::commit();

            LogActivityService::log(
                action: 'DELETE_MULTIPLE',
                module: 'Hasil Pemeriksaan Lain',
                description: 'Force delete multiple hasil pemeriksaan lain',
                oldData: $oldData
            );

            return response()->json([
                'success' => true,
                'message' => "Berhasil menghapus {$deletedCount} data secara permanen",
                'deleted_count' => $deletedCount
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }



    private function determineKeterangan($hasil, $rujukan)
    {
        $hasil = trim($hasil ?? '');
        $rujukan = trim($rujukan ?? '');

        if (empty($hasil) || empty($rujukan) || $rujukan === '-') {
            return '-';
        }

        $hasilNum = is_numeric($hasil) ? (float) $hasil : null;

        if ($hasilNum === null) {
            $hasilLower = strtolower($hasil);
            $rujukanLower = strtolower($rujukan);

            if (strpos($rujukanLower, 'negative') !== false || strpos($rujukanLower, 'negatif') !== false) {
                if (
                    strpos($hasilLower, 'negative') !== false || strpos($hasilLower, 'negatif') !== false ||
                    strpos($hasilLower, 'non-reactive') !== false || strpos($hasilLower, 'nonreactive') !== false
                ) {
                    return '-';
                } else {
                    return 'H';
                }
            } elseif (strpos($rujukanLower, 'positive') !== false || strpos($rujukanLower, 'positif') !== false) {
                if (
                    strpos($hasilLower, 'positive') !== false || strpos($hasilLower, 'positif') !== false ||
                    strpos($hasilLower, 'reactive') !== false || strpos($hasilLower, 'reaktif') !== false
                ) {
                    return '-';
                } else {
                    return 'L';
                }
            }

            return '-';
        }

        // Handle range: "X - Y"
        if (strpos($rujukan, '-') !== false && strpos($rujukan, '<') === false && strpos($rujukan, '>') === false) {
            $parts = explode('-', $rujukan);
            if (count($parts) === 2) {
                $min = trim($parts[0]);
                $max = trim($parts[1]);

                if (is_numeric($min) && is_numeric($max)) {
                    $min = (float) $min;
                    $max = (float) $max;

                    if ($hasilNum < $min) {
                        return 'L';
                    } elseif ($hasilNum > $max) {
                        return 'H';
                    } else {
                        return '-';
                    }
                }
            }
        }

        // Handle "< X"
        if (strpos($rujukan, '<') === 0) {
            $batas = trim(substr($rujukan, 1));
            if (is_numeric($batas)) {
                $batas = (float) $batas;
                if ($hasilNum >= $batas) {
                    return '-';
                } else {
                    return 'L';
                }
            }
        }

        return '-';
    }
    public function getJenisPemeriksaanList()
    {
        try {
            $jenis_pemeriksaan = DB::table('jenis_pemeriksaan_1')
                ->whereNull('deleted_at')
                ->orderBy('nama_pemeriksaan')
                ->get(['id_jenis_pemeriksaan_1', 'nama_pemeriksaan']);

            return response()->json([
                'success' => true,
                'data' => $jenis_pemeriksaan
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function storeManual(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'no_lab' => 'required|exists:pasien,no_lab',
            'jenis_pemeriksaan' => 'required|string|max:100',
            'id_data_pemeriksaan' => 'required|exists:data_pemeriksaan,id_data_pemeriksaan'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {

            /**
             * ==============================
             * JEDA ANTRIAN (PENTING)
             * ==============================
             * - Menghindari tabrakan insert
             * - Aman untuk input massal (±50 data)
             * - 100ms per request
             */
            usleep(100000); // 0.5 detik

            $dataPemeriksaan = DataPemeriksaan::findOrFail($request->id_data_pemeriksaan);

            $hasilLain = HasilPemeriksaanLain::create([
                'no_lab' => $request->no_lab,
                'jenis_pengujian' => $dataPemeriksaan->data_pemeriksaan,
                'id_data_pemeriksaan' => $request->id_data_pemeriksaan,
                'hasil_pengujian' => $request->hasil_pengujian,
                'satuan_hasil_pengujian' => $dataPemeriksaan->satuan,
                'rujukan' => $dataPemeriksaan->rujukan,
                'keterangan' => $request->hasil_pengujian
                    ? $this->determineKeterangan($request->hasil_pengujian, $dataPemeriksaan->rujukan)
                    : '-',
                'status_pemeriksaan' => 'selesai'
            ]);

            Pasien::where('no_lab', $request->no_lab)
                ->update(['updated_at' => now()->timezone('Asia/Jakarta')]);

            DB::commit();

            LogActivityService::log(
                action: 'CREATE',
                module: 'Hasil Pemeriksaan Lain',
                description: 'Tambah hasil pemeriksaan lain manual dengan no_lab: ' . $request->no_lab,
                newData: $hasilLain->toArray()
            );

            return response()->json([
                'success' => true,
                'message' => 'Data pemeriksaan berhasil ditambahkan',
                'data' => $hasilLain
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }


    public function searchDataPemeriksaan(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'search' => 'nullable|string|max:100',
                'jenis_pemeriksaan' => 'nullable|string|max:100'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $search = $request->input('search', '');
            $jenisPemeriksaan = $request->input('jenis_pemeriksaan', '');

            $query = DB::table('data_pemeriksaan as dp')
                ->select(
                    'dp.id_data_pemeriksaan',
                    'dp.data_pemeriksaan',
                    'dp.satuan',
                    'dp.rujukan',
                    'dp.ch',
                    'dp.cl',
                    'dp.metode',
                    'jp1.nama_pemeriksaan as jenis_pemeriksaan'
                )
                ->leftJoin('jenis_pemeriksaan_1 as jp1', 'dp.id_jenis_pemeriksaan_1', '=', 'jp1.id_jenis_pemeriksaan_1')
                ->whereNull('dp.deleted_at')
                ->whereNull('jp1.deleted_at');

            // Filter by jenis pemeriksaan jika ada
            if (!empty($jenisPemeriksaan)) {
                $query->where('jp1.nama_pemeriksaan', '=', $jenisPemeriksaan);
            }

            // Search term jika ada
            if (!empty($search) && strlen($search) >= 2) {
                $searchTerm = '%' . $search . '%';
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('dp.data_pemeriksaan', 'ILIKE', $searchTerm)
                      ->orWhere('dp.id_data_pemeriksaan', 'ILIKE', $searchTerm)
                      ->orWhere('dp.rujukan', 'ILIKE', $searchTerm);
                });
            }

            $results = $query->orderBy('dp.data_pemeriksaan')
                            ->limit(20)
                            ->get();

            LogActivityService::log(
                action: 'READ',
                module: 'Hasil Pemeriksaan Lain',
                description: 'User melakukan pencarian data pemeriksaan'
            );

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil ditemukan',
                'data' => $results
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getPemeriksaanByKode(Request $request)
    {
        $request->validate([
            'id_data_pemeriksaan' => 'required|string'
        ]);

        $dataPemeriksaan = DB::table('data_pemeriksaan as dp')
            ->where('dp.id_data_pemeriksaan', $request->id_data_pemeriksaan)
            ->whereNull('dp.deleted_at')
            ->select(
                'dp.id_data_pemeriksaan',
                'dp.data_pemeriksaan',
                'dp.satuan',
                'dp.rujukan',
                'dp.ch',
                'dp.cl'
            )
            ->first();

        if (!$dataPemeriksaan) {
            return response()->json([
                'success' => false,
                'message' => 'Data pemeriksaan tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $dataPemeriksaan
        ]);
    }

    public function updateHasilPengujian(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'hasil_pengujian' => 'nullable|string|max:100',
            'keterangan' => 'nullable|string|max:10'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $hasilLain = HasilPemeriksaanLain::findOrFail($id);

            $oldData = $hasilLain->toArray();

            $hasilLain->update([
                'hasil_pengujian' => $request->hasil_pengujian,
                'keterangan' => $request->keterangan ?? '-',
                'updated_at' => now()->timezone('Asia/Jakarta')
            ]);

            Pasien::where('no_lab', $hasilLain->no_lab)
                ->update(['updated_at' => now()->timezone('Asia/Jakarta')]);

            DB::commit();

            LogActivityService::log(
                action: 'UPDATE',
                module: 'Hasil Pemeriksaan Lain',
                description: 'Update hasil pengujian lain dengan ID: ' . $id,
                oldData: $oldData,
                newData: $hasilLain->toArray()
            );

            return response()->json([
                'success' => true,
                'message' => 'Hasil pengujian berhasil diperbarui',
                'data' => $hasilLain
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }



}

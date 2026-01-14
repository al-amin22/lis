<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class LisMappingController extends Controller
{
    // Search kode pemeriksaan
    public function searchKodePemeriksaan(Request $request)
    {
        $search = $request->input('search');
        $analysis = $request->input('analysis');

        $query = DB::table('data_pemeriksaan')
            ->select('id_data_pemeriksaan', 'data_pemeriksaan', 'satuan', 'rujukan', 'metode');

        // Cari berdasarkan kode atau nama pemeriksaan
        $query->where(function ($q) use ($search) {
            $q->where('id_data_pemeriksaan', 'ILIKE', "%{$search}%")
                ->orWhere('data_pemeriksaan', 'ILIKE', "%{$search}%");
        });

        // Jika ada analysis, coba match dengan data pemeriksaan
        if ($analysis) {
            $query->orWhere('data_pemeriksaan', 'ILIKE', "%{$analysis}%");
        }

        $results = $query->limit(10)->get();

        return response()->json([
            'success' => true,
            'data' => $results
        ]);
    }

    // Update kode pemeriksaan dan mapping
    public function updateKodePemeriksaan(Request $request)
    {
        DB::beginTransaction();

        try {
            $idPemeriksaanKimia = $request->input('id_pemeriksaan_kimia');
            $kodePemeriksaan = $request->input('id_data_pemeriksaan');
            $analysis = $request->input('analysis');

            // 1. Update id_data_pemeriksaan di tabel pemeriksaan_kimia
            DB::table('pemeriksaan_kimia')
                ->where('id_pemeriksaan_kimia', $idPemeriksaanKimia)
                ->update([
                    'id_data_pemeriksaan' => $kodePemeriksaan,
                    'updated_at' => now()
                ]);

            // 2. Cek apakah sudah ada mapping di lis_mapping
            $existingMapping = DB::table('lis_mapping')
                ->where('lis', $analysis)
                ->first();

            // 3. Jika belum ada, buat mapping baru
            if (!$existingMapping) {
                DB::table('lis_mapping')->insert([
                    'lis' => $analysis,
                    'id_data_pemeriksaan' => $kodePemeriksaan,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } else {
                // Jika sudah ada, update jika kode berbeda
                if ($existingMapping->id_data_pemeriksaan !== $kodePemeriksaan) {
                    DB::table('lis_mapping')
                        ->where('lis', $analysis)
                        ->update([
                            'id_data_pemeriksaan' => $kodePemeriksaan,
                            'updated_at' => now()
                        ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Kode pemeriksaan berhasil dipetakan',
                'mapped_lis' => $analysis,
                'mapped_kode' => $kodePemeriksaan
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal memetakan kode pemeriksaan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function saveManualRow(Request $request)
    {
        try {
            // Validasi
            $request->validate([
                'analysis' => 'required',
                'id_data_pemeriksaan' => 'required',
                'no_lab' => 'required',
            ]);

            // Insert data baru
            $id = DB::table('pemeriksaan_kimia')->insertGetId([
                'no_lab' => $request->no_lab,
                'analysis' => $request->analysis,
                'id_data_pemeriksaan' => $request->id_data_pemeriksaan,
                'satuan_hasil_pengujian' => $request->satuan,
                'rujukan' => $request->rujukan,
                'method' => $request->method,
                'hasil_pengujian' => $request->hasil_pengujian,
                'keterangan' => $request->keterangan,
                'created_at' => now(),
                'updated_at' => now(),
            ], 'id_pemeriksaan_kimia'); // <-- tambah 'id_pemeriksaan_kimia' di sini

            return response()->json([
                'success' => true,
                'id_pemeriksaan_kimia' => $id, // return ID yang baru dibuat
                'message' => 'Data berhasil disimpan'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateHasilRealtime(Request $request)
    {
        try {
            $request->validate([
                'id_pemeriksaan_kimia' => 'required|integer',
                'hasil_pengujian' => 'nullable',
                'keterangan' => 'nullable|string|max:5',
            ]);

            $updated = DB::table('pemeriksaan_kimia')
                ->where('id_pemeriksaan_kimia', $request->id_pemeriksaan_kimia)
                ->update([
                    'hasil_pengujian' => $request->hasil_pengujian,
                    'keterangan' => $request->keterangan,
                    'updated_at' => now(),
                ]);

            return response()->json([
                'success' => true,
                'updated' => $updated,
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    public function updateRow(Request $request)
    {
        try {
            // Validasi - pastikan id_pemeriksaan_kimia ada
            $request->validate([
                'id_pemeriksaan_kimia' => 'required|integer',
                'id_data_pemeriksaan' => 'required',
            ]);

            // Update existing row
            DB::table('pemeriksaan_kimia')
                ->where('id_pemeriksaan_kimia', $request->id_pemeriksaan_kimia)
                ->update([
                    'id_data_pemeriksaan' => $request->id_data_pemeriksaan,
                    'analysis' => $request->analysis,
                    'satuan_hasil_pengujian' => $request->satuan,
                    'rujukan' => $request->rujukan,
                    'method' => $request->method,
                    'hasil_pengujian' => $request->hasil_pengujian,
                    'keterangan' => $request->keterangan,
                    'updated_at' => now(),
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diperbarui'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal update: ' . $e->getMessage()
            ], 500);
        }
    }

    public function resetKodePemeriksaan(Request $request)
    {
        DB::beginTransaction();

        try {
            $idPemeriksaanKimia = $request->input('id_pemeriksaan_kimia');

            // 1. Dapatkan data sebelum reset untuk logging
            $data = DB::table('pemeriksaan_kimia')
                ->where('id_pemeriksaan_kimia', $idPemeriksaanKimia)
                ->first();

            if (!$data) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data pemeriksaan tidak ditemukan'
                ], 404);
            }

            // 2. Reset id_data_pemeriksaan menjadi NULL
            DB::table('pemeriksaan_kimia')
                ->where('id_pemeriksaan_kimia', $idPemeriksaanKimia)
                ->update([
                    'id_data_pemeriksaan' => null,
                    'updated_at' => now()
                ]);

            // 3. Hapus mapping di lis_mapping?
            // Opsi: Biarkan mapping tetap untuk referensi masa depan
            // Atau: Hapus jika ingin mapping baru setiap kali

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Mapping berhasil direset',
                'reset_data' => [
                    'id_pemeriksaan_kimia' => $idPemeriksaanKimia,
                    'analysis' => $data->analysis,
                    'previous_kode' => $data->id_data_pemeriksaan
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal reset mapping: ' . $e->getMessage()
            ], 500);
        }
    }

    public function saveManualRowHematology(Request $request)
    {
        try {
            $request->validate([
                'jenis_pengujian' => 'required',
                'id_data_pemeriksaan' => 'required',
                'no_lab' => 'required',
            ]);

            // Kunci sesuai UNIQUE CONSTRAINT database
            $where = [
                'no_lab' => $request->no_lab,
                'jenis_pengujian' => $request->jenis_pengujian,
            ];

            // Cek apakah sudah ada
            $existing = DB::table('pemeriksaan_hematology')
                ->where($where)
                ->first();

            if ($existing) {
                // ========================
                // UPDATE (row yg sama)
                // ========================
                DB::table('pemeriksaan_hematology')
                    ->where('id_pemeriksaan_hematology', $existing->id_pemeriksaan_hematology)
                    ->update([
                        'id_data_pemeriksaan' => $request->id_data_pemeriksaan,
                        'satuan_hasil_pengujian' => $request->satuan_hasil_pengujian,
                        'rujukan' => $request->rujukan,
                        'hasil_pengujian' => $request->hasil_pengujian,
                        'keterangan' => $request->keterangan,
                        'status_pemeriksaan' => 'selesai',
                        'updated_at' => now(),
                    ]);

                return response()->json([
                    'success' => true,
                    'mode' => 'update',
                    'id_pemeriksaan_hematology' => $existing->id_pemeriksaan_hematology,
                    'message' => 'Data diperbarui (no_lab + jenis_pengujian)'
                ]);
            }

            // ========================
            // INSERT (jika belum ada)
            // ========================
            $id = DB::table('pemeriksaan_hematology')->insertGetId([
                'no_lab' => $request->no_lab,
                'jenis_pengujian' => $request->jenis_pengujian,
                'id_data_pemeriksaan' => $request->id_data_pemeriksaan,
                'satuan_hasil_pengujian' => $request->satuan_hasil_pengujian,
                'rujukan' => $request->rujukan,
                'hasil_pengujian' => $request->hasil_pengujian,
                'keterangan' => $request->keterangan,
                'status_pemeriksaan' => 'selesai',
                'created_at' => now(),
                'updated_at' => now(),
            ], 'id_pemeriksaan_hematology');

            return response()->json([
                'success' => true,
                'mode' => 'insert',
                'id_pemeriksaan_hematology' => $id,
                'message' => 'Data baru disimpan'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateRowHematology(Request $request)
    {
        try {
            // Validasi - pastikan id_pemeriksaan_hematology ada
            $request->validate([
                'id_pemeriksaan_hematology' => 'required|integer',
                'id_data_pemeriksaan' => 'required',
            ]);

            // Update existing row
            DB::table('pemeriksaan_hematology')
                ->where('id_pemeriksaan_hematology', $request->id_pemeriksaan_hematology)
                ->update([
                    'id_data_pemeriksaan' => $request->id_data_pemeriksaan,
                    'jenis_pengujian' => $request->jenis_pengujian,
                    'satuan_hasil_pengujian' => $request->satuan_hasil_pengujian,
                    'rujukan' => $request->rujukan,
                    'hasil_pengujian' => $request->hasil_pengujian,
                    'keterangan' => $request->keterangan,
                    'updated_at' => now(),
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diperbarui'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal update: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateHasilRealtimeHematology(Request $request)
    {
        try {
            DB::table('pemeriksaan_hematology')
                ->where('id_pemeriksaan_hematology', $request->id_pemeriksaan_hematology)
                ->update([
                    'hasil_pengujian' => $request->hasil_pengujian,
                    'keterangan' => $request->keterangan,
                    'updated_at' => now()
                ]);

            return response()->json([
                'success' => true
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteManualRowHematology($id)
    {
        try {
            $deleted = DB::table('pemeriksaan_hematology')
                ->where('id_pemeriksaan_hematology', $id)
                ->delete();

            if ($deleted) {
                return response()->json([
                    'status' => true,
                    'message' => 'Data hematologi berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menghapus: ' . $e->getMessage()
            ], 500);
        }
    }
}

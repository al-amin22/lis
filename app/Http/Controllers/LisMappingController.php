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
            ->select('kode_pemeriksaan', 'data_pemeriksaan', 'satuan', 'rujukan', 'metode');

        // Cari berdasarkan kode atau nama pemeriksaan
        $query->where(function ($q) use ($search) {
            $q->where('kode_pemeriksaan', 'ILIKE', "%{$search}%")
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
            $kodePemeriksaan = $request->input('kode_pemeriksaan');
            $analysis = $request->input('analysis');

            // 1. Update kode_pemeriksaan di tabel pemeriksaan_kimia
            DB::table('pemeriksaan_kimia')
                ->where('id_pemeriksaan_kimia', $idPemeriksaanKimia)
                ->update([
                    'kode_pemeriksaan' => $kodePemeriksaan,
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
                    'kode_pemeriksaan' => $kodePemeriksaan,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } else {
                // Jika sudah ada, update jika kode berbeda
                if ($existingMapping->kode_pemeriksaan !== $kodePemeriksaan) {
                    DB::table('lis_mapping')
                        ->where('lis', $analysis)
                        ->update([
                            'kode_pemeriksaan' => $kodePemeriksaan,
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

            // 2. Reset kode_pemeriksaan menjadi NULL
            DB::table('pemeriksaan_kimia')
                ->where('id_pemeriksaan_kimia', $idPemeriksaanKimia)
                ->update([
                    'kode_pemeriksaan' => null,
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
                    'previous_kode' => $data->kode_pemeriksaan
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
}

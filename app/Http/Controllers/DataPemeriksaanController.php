<?php

namespace App\Http\Controllers;

use App\Models\DataPemeriksaan;
use App\Models\JenisPemeriksaan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Services\LogActivityService;
use Illuminate\Container\Attributes\Log;
use Illuminate\Support\Facades\DB;
use App\Models\DetailDataPemeriksaan;

class DataPemeriksaanController extends Controller
{
    public function index()
    {
        $dataPemeriksaans = DataPemeriksaan::with('jenisPemeriksaan')
            ->orderBy('updated_at', 'desc')
            ->get();

        LogActivityService::log(
            action: 'READ',
            module: 'Pemeriksaan',
            description: 'User mengakses daftar pemeriksaan laboratorium'
        );

        $jenisPemeriksaans = JenisPemeriksaan::orderBy('nama_pemeriksaan')->get();

        LogActivityService::log(
            action: 'READ',
            module: 'Jenis Pemeriksaan',
            description: 'User mengakses daftar jenis pemeriksaan laboratorium'
        );

        return view('data-pemeriksaan.index', compact('dataPemeriksaans', 'jenisPemeriksaans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_jenis_pemeriksaan_1' => 'required|exists:jenis_pemeriksaan_1,id_jenis_pemeriksaan_1',
            'data_pemeriksaan' => 'required|string|max:255',
            'lis' => 'nullable|string|max:100',
            'satuan' => 'nullable|string|max:50',
            'rujukan' => 'nullable|string',
            'metode' => 'nullable|string|max:100',
            'urutan' => 'nullable|integer',
            'ch' => 'nullable|string|max:50',
            'cl' => 'nullable|string|max:50',
        ]);

        // Generate kode otomatis
        $kode = $this->generateKodePemeriksaan($request->id_jenis_pemeriksaan_1);

        DataPemeriksaan::create([
            'id_data_pemeriksaan' => $kode,
            'id_jenis_pemeriksaan_1' => $request->id_jenis_pemeriksaan_1,
            'data_pemeriksaan' => $request->data_pemeriksaan,
            'lis' => $request->lis,
            'satuan' => $request->satuan,
            'rujukan' => $request->rujukan,
            'metode' => $request->metode,
            'urutan' => $request->urutan,
            'ch' => $request->ch,
            'cl' => $request->cl,
        ]);

        LogActivityService::log(
            action: 'CREATE',
            module: 'Pemeriksaan',
            description: 'Menambahkan data pemeriksaan baru dengan kode: ' . $kode
        );

        return redirect()->route('pasien.index.data.pemeriksaan')
            ->with('success', 'Data pemeriksaan berhasil ditambahkan.');
    }

    public function storeBatch(Request $request)
    {
        $request->validate([
            'id_jenis_pemeriksaan_1' => 'required|exists:jenis_pemeriksaan_1,id_jenis_pemeriksaan_1',
            'nama_pemeriksaan' => 'required|array|min:1',
            'nama_pemeriksaan.*' => 'required|string|max:255',
            'lis' => 'nullable|array',
            'lis.*' => 'nullable|string|max:100',
            'satuan' => 'nullable|array',
            'satuan.*' => 'nullable|string|max:50',
            'rujukan' => 'nullable|array',
            'rujukan.*' => 'nullable|string',
            'metode' => 'nullable|array',
            'metode.*' => 'nullable|string|max:100',
            'urutan' => 'nullable|array',
            'urutan.*' => 'nullable|integer',
            'ch' => 'nullable|array',
            'ch.*' => 'nullable|string|max:50',
            'cl' => 'nullable|array',
            'cl.*' => 'nullable|string|max:50',
        ]);

        $jenisPemeriksaanId = $request->id_jenis_pemeriksaan_1;
        $createdCount = 0;

        foreach ($request->nama_pemeriksaan as $index => $nama) {
            if (!empty(trim($nama))) {
                // Generate kode otomatis untuk setiap item
                $kode = $this->generateKodePemeriksaan($jenisPemeriksaanId);

                DataPemeriksaan::create([
                    'id_data_pemeriksaan' => $kode,
                    'id_jenis_pemeriksaan_1' => $jenisPemeriksaanId,
                    'data_pemeriksaan' => trim($nama),
                    'lis' => $request->lis[$index] ?? null,
                    'satuan' => $request->satuan[$index] ?? null,
                    'rujukan' => $request->rujukan[$index] ?? null,
                    'metode' => $request->metode[$index] ?? null,
                    'urutan' => $request->urutan[$index] ?? null,
                    'ch' => $request->ch[$index] ?? null,
                    'cl' => $request->cl[$index] ?? null,
                ]);

                LogActivityService::log(
                    action: 'CREATE',
                    module: 'Pemeriksaan',
                    description: 'Menambahkan data pemeriksaan baru dengan kode: ' . $kode
                );

                $createdCount++;
            }
        }

        LogActivityService::log(
            action: 'CREATE',
            module: 'Pemeriksaan',
            description: "Menambahkan $createdCount data pemeriksaan baru secara batch."
        );

        return redirect()->route('pasien.index.data.pemeriksaan')
            ->with('success', "Berhasil menambahkan $createdCount data pemeriksaan.");
    }
    public function show($id)
    {
        $dataPemeriksaan = DataPemeriksaan::with([
            'jenisPemeriksaan',
            'detailConditions'
        ])->findOrFail($id);

        return view('data-pemeriksaan.show', compact('dataPemeriksaan'));
    }

    public function updateInline(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'field' => 'required',
            'value' => 'nullable'
        ]);

        $dp = DataPemeriksaan::findOrFail($request->id);
        $dp->{$request->field} = $request->value;
        $dp->save();

        return response()->json(['success' => true]);
    }

    // UPDATE DETAIL DATA PEMERIKSAAN (INLINE)
    public function updateDetailInline(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'field' => 'required',
            'value' => 'nullable'
        ]);

        $detail = DetailDataPemeriksaan::findOrFail($request->id);
        $detail->{$request->field} = $request->value;
        $detail->save();

        return response()->json(['success' => true]);
    }

    public function update(Request $request, $id_data_pemeriksaan)
    {
        $dataPemeriksaan = DataPemeriksaan::findOrFail($id_data_pemeriksaan);

        $request->validate([
            'id_jenis_pemeriksaan_1' => 'required|exists:jenis_pemeriksaan_1,id_jenis_pemeriksaan_1',
            'data_pemeriksaan' => 'required|string|max:255',
            'lis' => 'nullable|string|max:100',
            'satuan' => 'nullable|string|max:50',
            'rujukan' => 'nullable|string',
            'metode' => 'nullable|string|max:100',
            'urutan' => 'nullable|integer',
            'ch' => 'nullable|string|max:50',
            'cl' => 'nullable|string|max:50',
            'kode_uji_pemeriksaan' => 'nullable|string|max:50',
        ]);

        $oldData = $dataPemeriksaan->toArray();

        $dataPemeriksaan->update([
            'id_data_pemeriksaan' => $id_data_pemeriksaan,
            'id_jenis_pemeriksaan_1' => $request->id_jenis_pemeriksaan_1,
            'data_pemeriksaan' => $request->data_pemeriksaan,
            'lis' => $request->lis,
            'satuan' => $request->satuan,
            'rujukan' => $request->rujukan,
            'metode' => $request->metode,
            'urutan' => $request->urutan,
            'ch' => $request->ch,
            'cl' => $request->cl,
            'kode_uji_pemeriksaan' => $request->kode_uji_pemeriksaan,
        ]);

        LogActivityService::log(
            action: 'UPDATE',
            module: 'Pemeriksaan',
            description: 'Mengupdate data pemeriksaan dengan kode: ' . $id_data_pemeriksaan,
            oldData: $oldData,
            newData: $dataPemeriksaan->toArray()
        );

        return redirect()->route('pasien.index.data.pemeriksaan')
            ->with('success', 'Data pemeriksaan berhasil diupdate.');
    }

    public function updateBatchByJenis(Request $request, $idJenis)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id_data_pemeriksaan' => 'required|exists:data_pemeriksaan,id_data_pemeriksaan',
            'items.*.data_pemeriksaan' => 'required|string|max:100',
            'items.*.satuan' => 'nullable|string|max:50',
            'items.*.rujukan' => 'nullable|string|max:100',
            'items.*.metode' => 'nullable|string|max:100',
            'items.*.urutan' => 'nullable|integer',
            'items.*.ch' => 'nullable|string|max:50',
            'items.*.cl' => 'nullable|string|max:50',
            'items.*.kode_uji_pemeriksaan' => 'nullable|string|max:50',
        ]);

        DB::transaction(function () use ($request, $idJenis) {

            foreach ($request->items as $item) {

                DataPemeriksaan::where('id_data_pemeriksaan', $item['id_data_pemeriksaan'])
                    ->where('id_jenis_pemeriksaan_1', $idJenis)
                    ->update([
                        'data_pemeriksaan' => $item['data_pemeriksaan'],
                        'satuan' => $item['satuan'] ?? null,
                        'rujukan' => $item['rujukan'] ?? null,
                        'metode' => $item['metode'] ?? null,
                        'urutan' => $item['urutan'] ?? null,
                        'ch' => $item['ch'] ?? null,
                        'cl' => $item['cl'] ?? null,
                        'kode_uji_pemeriksaan' => $item['kode_uji_pemeriksaan'] ?? null,
                        'updated_at' => now(),
                    ]);
            }
        });

        // Log aktivitas (opsional tapi disarankan)
        LogActivityService::log(
            action: 'UPDATE_BATCH',
            module: 'Data Pemeriksaan',
            description: 'Update batch data pemeriksaan berdasarkan jenis ID: ' . $idJenis
        );

        return back()->with('success', 'Update batch data pemeriksaan berhasil.');
    }


    public function destroy($id_data_pemeriksaan)
    {
        try {
            $dataPemeriksaan = DataPemeriksaan::findOrFail($id_data_pemeriksaan);
            $oldData = $dataPemeriksaan->toArray();

            // Cek dulu apakah ada relasi yang masih mengikat
            if ($dataPemeriksaan->kimia()->exists()) {
                return redirect()->route('pasien.index.data.pemeriksaan')
                    ->with('error', 'Data pemeriksaan tidak dapat dihapus karena masih digunakan di tabel Kimia.');
            }

            if ($dataPemeriksaan->hematology()->exists()) {
                return redirect()->route('pasien.index.data.pemeriksaan')
                    ->with('error', 'Data pemeriksaan tidak dapat dihapus karena masih digunakan di tabel Kimia.');
            }

            if ($dataPemeriksaan->hasilPemeriksaan()->exists()) {
                return redirect()->route('pasien.index.data.pemeriksaan')
                    ->with('error', 'Data pemeriksaan tidak dapat dihapus karena masih digunakan di tabel Hasil Pemeriksaan Lain.');
            }

            // Jika aman, hapus
            $dataPemeriksaan->delete();

            LogActivityService::log(
                action: 'DELETE',
                module: 'Pemeriksaan',
                description: 'Menghapus data pemeriksaan dengan kode: ' . $id_data_pemeriksaan,
                oldData: $oldData
            );

            return redirect()->route('pasien.index.data.pemeriksaan')
                ->with('success', 'Data pemeriksaan berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            LogActivityService::log(
                action: 'ERROR',
                module: 'Pemeriksaan',
                description: 'Gagal menghapus data pemeriksaan dengan kode: ' . $id_data_pemeriksaan . '. Error: ' . $e->getMessage()
            );
            // Tangani error foreign key atau error lainnya
            if ($e->getCode() == '23503') { // kode Postgres foreign key violation
                return redirect()->route('pasien.index.data.pemeriksaan')
                    ->with('error', 'Data pemeriksaan tidak dapat dihapus karena masih digunakan di tabel lain.');
            }

            return redirect()->route('pasien.index.data.pemeriksaan')
                ->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        } catch (\Exception $e) {
            return redirect()->route('pasien.index.data.pemeriksaan')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    /**
     * Generate kode pemeriksaan otomatis
     * Format: JP{jenis_id}-{increment}
     */
    private function generateKodePemeriksaan($jenisId)
    {
        // Cari jumlah data untuk jenis ini
        $count = DataPemeriksaan::where('id_jenis_pemeriksaan_1', $jenisId)->count();

        // Ambil inisial dari jenis pemeriksaan
        $jenis = JenisPemeriksaan::find($jenisId);
        $inisial = strtoupper(substr(preg_replace('/[^A-Z]/', '', $jenis->nama_pemeriksaan), 0, 3));

        if (empty($inisial)) {
            $inisial = 'JP';
        }

        $nextNumber = $count + 1;
        $kode = $inisial . '-' . str_pad($jenisId, 3, '0', STR_PAD_LEFT) . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        LogActivityService::log(
            action: 'GENERATE',
            module: 'Pemeriksaan',
            description: 'Mengenerate kode pemeriksaan otomatis: ' . $kode
        );

        return $kode;
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\DataPemeriksaan;
use App\Models\JenisPemeriksaan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Services\LogActivityService;
use Illuminate\Container\Attributes\Log;

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
        ]);

        // Generate kode otomatis
        $kode = $this->generateKodePemeriksaan($request->id_jenis_pemeriksaan_1);

        DataPemeriksaan::create([
            'kode_pemeriksaan' => $kode,
            'id_jenis_pemeriksaan_1' => $request->id_jenis_pemeriksaan_1,
            'data_pemeriksaan' => $request->data_pemeriksaan,
            'lis' => $request->lis,
            'satuan' => $request->satuan,
            'rujukan' => $request->rujukan,
            'metode' => $request->metode,
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
        ]);

        $jenisPemeriksaanId = $request->id_jenis_pemeriksaan_1;
        $createdCount = 0;

        foreach ($request->nama_pemeriksaan as $index => $nama) {
            if (!empty(trim($nama))) {
                // Generate kode otomatis untuk setiap item
                $kode = $this->generateKodePemeriksaan($jenisPemeriksaanId);

                DataPemeriksaan::create([
                    'kode_pemeriksaan' => $kode,
                    'id_jenis_pemeriksaan_1' => $jenisPemeriksaanId,
                    'data_pemeriksaan' => trim($nama),
                    'lis' => $request->lis[$index] ?? null,
                    'satuan' => $request->satuan[$index] ?? null,
                    'rujukan' => $request->rujukan[$index] ?? null,
                    'metode' => $request->metode[$index] ?? null,
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

    public function update(Request $request, $kode_pemeriksaan)
    {
        $dataPemeriksaan = DataPemeriksaan::findOrFail($kode_pemeriksaan);

        $request->validate([
            'id_jenis_pemeriksaan_1' => 'required|exists:jenis_pemeriksaan_1,id_jenis_pemeriksaan_1',
            'data_pemeriksaan' => 'required|string|max:255',
            'lis' => 'nullable|string|max:100',
            'satuan' => 'nullable|string|max:50',
            'rujukan' => 'nullable|string',
            'metode' => 'nullable|string|max:100',
        ]);

        $oldData = $dataPemeriksaan->toArray();

        $dataPemeriksaan->update([
            'kode_pemeriksaan' => $kode_pemeriksaan,
            'id_jenis_pemeriksaan_1' => $request->id_jenis_pemeriksaan_1,
            'data_pemeriksaan' => $request->data_pemeriksaan,
            'lis' => $request->lis,
            'satuan' => $request->satuan,
            'rujukan' => $request->rujukan,
            'metode' => $request->metode,
        ]);

        LogActivityService::log(
            action: 'UPDATE',
            module: 'Pemeriksaan',
            description: 'Mengupdate data pemeriksaan dengan kode: ' . $kode_pemeriksaan,
            oldData: $oldData,
            newData: $dataPemeriksaan->toArray()
        );

        return redirect()->route('pasien.index.data.pemeriksaan')
            ->with('success', 'Data pemeriksaan berhasil diupdate.');
    }

    public function destroy($kode_pemeriksaan)
    {
        try {
            $dataPemeriksaan = DataPemeriksaan::findOrFail($kode_pemeriksaan);
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

            // Jika aman, hapus
            $dataPemeriksaan->delete();

            LogActivityService::log(
                action: 'DELETE',
                module: 'Pemeriksaan',
                description: 'Menghapus data pemeriksaan dengan kode: ' . $kode_pemeriksaan,
                oldData: $oldData
            );

            return redirect()->route('pasien.index.data.pemeriksaan')
                ->with('success', 'Data pemeriksaan berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            LogActivityService::log(
                action: 'ERROR',
                module: 'Pemeriksaan',
                description: 'Gagal menghapus data pemeriksaan dengan kode: ' . $kode_pemeriksaan . '. Error: ' . $e->getMessage()
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

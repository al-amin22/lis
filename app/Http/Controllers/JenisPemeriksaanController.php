<?php

namespace App\Http\Controllers;

use App\Models\JenisPemeriksaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\LogActivityService;

class JenisPemeriksaanController extends Controller
{
    public function index()
    {
        $jenisPemeriksaans = JenisPemeriksaan::orderBy('updated_at', 'desc')->get();
        LogActivityService::log(
            action: 'READ',
            module: 'Hasil Pemeriksaan Lain',
            description: 'User mengambil data pemeriksaan untuk jenis: ' . $jenisPemeriksaans
        );
        return view('jenis-pemeriksaan.index', compact('jenisPemeriksaans'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_pemeriksaan' => 'required|string|max:255|unique:jenis_pemeriksaan,nama_pemeriksaan',
        ]);

        // Simpan data
        JenisPemeriksaan::create([
            'nama_pemeriksaan' => $request->nama_pemeriksaan,
        ]);

        LogActivityService::log(
            action: 'CREATE',
            module: 'Jenis Pemeriksaan',
            description: 'Menambahkan jenis pemeriksaan baru: ' . $request->nama_pemeriksaan
        );

        // Redirect ke route yang BENAR
        return redirect()->route('pasien.index.jenis.pemeriksaan')
            ->with('success', 'Jenis pemeriksaan berhasil ditambahkan.');
    }


    public function storeBatch(Request $request)
    {
        $request->validate([
            'nama_pemeriksaan' => 'required|string',
        ]);

        $jenisPemeriksaanArray = array_filter(
            array_map(
                'trim',
                explode("\n", $request->nama_pemeriksaan)
            )
        );

        $createdCount = 0;
        $existingCount = 0;

        foreach ($jenisPemeriksaanArray as $nama) {
            if (!empty($nama)) {
                // Cek apakah sudah ada
                $existing = JenisPemeriksaan::where('nama_pemeriksaan', $nama)->first();

                if (!$existing) {
                    JenisPemeriksaan::create(['nama_pemeriksaan' => $nama]);
                    $createdCount++;
                } else {
                    $existingCount++;
                }
            }
        }

        $message = "Berhasil menambahkan $createdCount jenis pemeriksaan.";
        if ($existingCount > 0) {
            $message .= " $existingCount sudah ada.";
        }

        LogActivityService::log(
            action: 'CREATE_MULTIPLE',
            module: 'Jenis Pemeriksaan',
            description: 'Menambahkan ' . $createdCount . ' jenis pemeriksaan secara massal. ' . $existingCount . ' sudah ada.'
        );

        return redirect()->route('pasien.index.jenis.pemeriksaan')
            ->with('success', $message);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_pemeriksaan' => 'required|string|max:100'
        ]);

        // Ambil data berdasarkan id_jenis_pemeriksaan_1
        $jenis = JenisPemeriksaan::where('id_jenis_pemeriksaan_1', $id)->firstOrFail();

        $oldData = $jenis->toArray();

        // Cek duplikasi nama (hindari error UNIQUE)
        $exists = JenisPemeriksaan::where('nama_pemeriksaan', $request->nama_pemeriksaan)
            ->where('id_jenis_pemeriksaan_1', '!=', $id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['nama_pemeriksaan' => 'Nama pemeriksaan sudah digunakan.']);
        }

        // Update data
        $jenis->update([
            'nama_pemeriksaan' => $request->nama_pemeriksaan
        ]);

        LogActivityService::log(
            action: 'UPDATE',
            module: 'Jenis Pemeriksaan',
            description: 'Memperbarui jenis pemeriksaan dengan ID: ' . $id,
            oldData: $oldData,
            newData: $jenis->toArray()
        );

        return redirect()
            ->route('pasien.index.jenis.pemeriksaan')
            ->with('success', 'Jenis pemeriksaan berhasil diperbarui.');
    }



    public function destroy($id)
    {
        $jenisPemeriksaan = JenisPemeriksaan::findOrFail($id);
        $oldData = $jenisPemeriksaan->toArray();
        $jenisPemeriksaan->delete();

        LogActivityService::log(
            action: 'DELETE',
            module: 'Jenis Pemeriksaan',
            description: 'Menghapus jenis pemeriksaan dengan ID: ' . $id,
            oldData: $oldData
        );

        return redirect()->route('pasien.index.jenis.pemeriksaan')
            ->with('success', 'Jenis pemeriksaan berhasil dihapus.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use Illuminate\Http\Request;
use App\Services\LogActivityService;

class DokterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dokters = Dokter::withCount('pasien')->orderBy('id_dokter', 'desc')->paginate(10);
        LogActivityService::log(
            action: 'READ',
            module: 'Dokter',
            description: 'User mengakses daftar dokter'
        );

        return view('dokter.index', compact('dokters'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_dokter' => 'required|string|max:100',
            'alamat' => 'required|string',
            'no_telp' => 'required|string|max:15',
        ]);

        Dokter::create($request->all());

        LogActivityService::log(
            action: 'CREATE',
            module: 'Dokter',
            description: 'Menambahkan dokter baru: ' . $request->nama_dokter
        );

        return redirect()->back()->with('success', 'Dokter berhasil ditambahkan!');
    }

    /**
     * Store multiple resources in storage.
     */
    public function storeMultiple(Request $request)
    {
        $request->validate([
            'dokters' => 'required|array|min:1',
            'dokters.*.nama_dokter' => 'required|string|max:100',
            'dokters.*.alamat' => 'required|string',
            'dokters.*.no_telp' => 'required|string|max:15',
        ]);

        foreach ($request->dokters as $dokterData) {
            Dokter::create($dokterData);
        }

        LogActivityService::log(
            action: 'CREATE',
            module: 'Dokter',
            description: 'Menambahkan ' . count($request->dokters) . ' data dokter secara massal'
        );

        return redirect()->back()->with('success', count($request->dokters) . ' data dokter berhasil ditambahkan!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_dokter' => 'required|string|max:100',
            'alamat' => 'required|string',
            'no_telp' => 'required|string|max:15',
        ]);

        $oldData = Dokter::findOrFail($id)->toArray();

        $dokter = Dokter::findOrFail($id);
        $dokter->update($request->all());

        LogActivityService::log(
            action: 'UPDATE',
            module: 'Dokter',
            description: 'Memperbarui data dokter dengan ID: ' . $id,
            oldData: $oldData,
            newData: $dokter->toArray()
        );

        return redirect()->back()->with('success', 'Data dokter berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

        $dokter = Dokter::findOrFail($id);

        $oledData = $dokter->toArray();

        // Cek apakah dokter memiliki pasien
        if ($dokter->pasien()->exists()) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus dokter karena memiliki ' . $dokter->pasien()->count() . ' pasien terkait!');
        }

        $dokter->delete();

        LogActivityService::log(
            action: 'DELETE',
            module: 'Dokter',
            description: 'Menghapus data dokter dengan ID: ' . $id,
            oldData: $oledData
        );

        return redirect()->back()->with('success', 'Dokter berhasil dihapus!');
    }

    public function searchDokter(Request $request)
    {
        $search = $request->get('search', '');

        $dokters = Dokter::where('nama_dokter', 'LIKE', "%{$search}%")
            ->limit(10)
            ->get(['id_dokter', 'nama_dokter', 'no_telp']);

        return response()->json([
            'success' => true,
            'data' => $dokters
        ]);
    }

    public function createDokter(Request $request)
    {
        $request->validate([
            'nama_dokter' => 'required|string|max:255',
            'no_telp' => 'nullable|string|max:20'
        ]);

        try {
            // Cek apakah sudah ada
            $existing = Dokter::where('nama_dokter', $request->nama_dokter)->first();

            if ($existing) {
                return response()->json([
                    'success' => true,
                    'data' => $existing,
                    'message' => 'Dokter sudah ada'
                ]);
            }

            // Buat dokter baru
            $dokter = Dokter::create([
                'nama_dokter' => $request->nama_dokter,
                'no_telp' => $request->no_telp,
            ]);

            return response()->json([
                'success' => true,
                'data' => $dokter,
                'message' => 'Dokter baru berhasil dibuat'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat dokter: ' . $e->getMessage()
            ], 500);
        }
    }
}

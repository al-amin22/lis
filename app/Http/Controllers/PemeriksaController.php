<?php

namespace App\Http\Controllers;

use App\Models\Pemeriksa;
use Illuminate\Http\Request;
use App\Services\LogActivityService;
use Illuminate\Http\JsonResponse;
class PemeriksaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pemeriksa = Pemeriksa::withCount('pasien')->orderBy('id_pemeriksa', 'desc')->paginate(10);
        LogActivityService::log(
            action: 'READ',
            module: 'Pemeriksa',
            description: 'User mengakses daftar pemeriksa'
        );

        return view('pemeriksa.index', compact('pemeriksa'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_pemeriksa' => 'required|string|max:100',
            'alamat' => 'required|string',
            'no_telp' => 'required|string|max:15',
        ]);

        Pemeriksa::create($request->all());

        LogActivityService::log(
            action: 'CREATE',
            module: 'Pemeriksa',
            description: 'Menambahkan pemeriksa baru: ' . $request->nama_pemeriksa
        );

        return redirect()->back()->with('success', 'Pemeriksa berhasil ditambahkan!');
    }

    /**
     * Store multiple resources in storage.
     */
    public function storeMultiple(Request $request)
    {
        $request->validate([
            'pemeriksa' => 'required|array|min:1',
            'pemeriksa.*.nama_pemeriksa' => 'required|string|max:100',
            'pemeriksa.*.alamat' => 'required|string',
            'pemeriksa.*.no_telp' => 'required|string|max:15',
        ]);

        foreach ($request->pemeriksa as $pemeriksaData) {
            Pemeriksa::create($pemeriksaData);
        }

        LogActivityService::log(
            action: 'CREATE_MULTIPLE',
            module: 'Pemeriksa',
            description: 'Menambahkan ' . count($request->pemeriksa) . ' data pemeriksa baru'
        );

        return redirect()->back()->with('success', count($request->pemeriksa) . ' data pemeriksa berhasil ditambahkan!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_pemeriksa' => 'required|string|max:100',
            'alamat' => 'required|string',
            'no_telp' => 'required|string|max:15',
        ]);

        $pemeriksa = Pemeriksa::findOrFail($id);

        $oldData = $pemeriksa->toArray();
        $pemeriksa->update($request->all());

        LogActivityService::log(
            action: 'UPDATE',
            module: 'Pemeriksa',
            description: 'Memperbarui data pemeriksa dengan ID: ' . $id,
            oldData: $oldData,
            newData: $pemeriksa->toArray()
        );

        return redirect()->back()->with('success', 'Data pemeriksa berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $pemeriksa = Pemeriksa::findOrFail($id);

        $oldData = $pemeriksa->toArray();

        // Cek apakah kelas memiliki pasien
        if ($pemeriksa->pasien()->exists()) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus kelas karena memiliki ' . $pemeriksa->pasien()->count() . ' pasien terkait!');
        }

        $pemeriksa->delete();

        LogActivityService::log(
            action: 'DELETE',
            module: 'Pemeriksa',
            description: 'Menghapus pemeriksa dengan ID: ' . $id,
            oldData: $oldData
        );

        return redirect()->back()->with('success', 'Pemeriksa berhasil dihapus!');
    }

   public function searchPemeriksa(Request $request)
    {
        $search = $request->input('q', '');

        if (strlen($search) < 2) {
            return response()->json([]);
        }

        $pemeriksa = Pemeriksa::where('nama_pemeriksa', 'like', '%' . $search . '%')
            ->limit(15)
            ->get(['id_pemeriksa', 'nama_pemeriksa'])
            ->map(function ($item) {
                return [
                    'id'   => $item->id_pemeriksa,
                    'text' => $item->nama_pemeriksa,
                ];
            });

        return response()->json($pemeriksa);
    }





}

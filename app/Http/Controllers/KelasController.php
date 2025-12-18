<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\Request;
use App\Services\LogActivityService;
use PgSql\Lob;

class KelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kelas = Kelas::withCount('pasien')->orderBy('id_kelas', 'desc')->get();
        LogActivityService::log(
            action: 'READ',
            module: 'Kelas',
            description: 'User mengakses daftar kelas'
        );

        return view('kelas.index', compact('kelas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:100',
        ]);

        Kelas::create($request->all());

        LogActivityService::log(
            action: 'CREATE',
            module: 'Kelas',
            description: 'Menambahkan kelas baru: ' . $request->nama_kelas
        );

        return redirect()->back()->with('success', 'Kelas berhasil ditambahkan!');
    }

    /**
     * Store multiple resources in storage.
     */
    public function storeMultiple(Request $request)
    {
        $request->validate([
            'kelas' => 'required|array|min:1',
            'kelas.*.nama_kelas' => 'required|string|max:100',
        ]);

        foreach ($request->kelas as $kelasData) {
            Kelas::create($kelasData);
        }

        LogActivityService::log(
            action: 'CREATE_MULTIPLE',
            module: 'Kelas',
            description: 'Menambahkan ' . count($request->kelas) . ' kelas secara massal.'
        );

        return redirect()->back()->with('success', count($request->kelas) . ' data kelas berhasil ditambahkan!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:100',
        ]);

        $kelas = Kelas::findOrFail($id);

        $oldData = $kelas->toArray();
        $kelas->update($request->all());

        LogActivityService::log(
            action: 'UPDATE',
            module: 'Kelas',
            description: 'Memperbarui kelas dengan ID: ' . $id,
            oldData: $oldData,
            newData: $kelas->toArray()
        );

        return redirect()->back()->with('success', 'Data kelas berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $kelas = Kelas::findOrFail($id);

        $oldData = $kelas->toArray();

        // Cek apakah kelas memiliki pasien
        if ($kelas->pasien()->exists()) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus kelas karena memiliki ' . $kelas->pasien()->count() . ' pasien terkait!');
        }

        $kelas->delete();

        LogActivityService::log(
            action: 'DELETE',
            module: 'Kelas',
            description: 'Menghapus kelas dengan ID: ' . $id,
            oldData: $oldData
        );

        return redirect()->back()->with('success', 'Kelas berhasil dihapus!');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Ruangan;
use Illuminate\Http\Request;
use App\Services\LogActivityService;

class RuanganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ruangan = Ruangan::withCount('pasien')->orderBy('id_ruangan', 'desc')->paginate(10);
        LogActivityService::log(
            action: 'READ',
            module: 'Ruangan',
            description: 'User mengakses daftar ruangan'
        );
        return view('ruangan.index', compact('ruangan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_ruangan' => 'required|string|max:100',
        ]);

        Ruangan::create($request->all());

        LogActivityService::log(
            action: 'CREATE',
            module: 'Ruangan',
            description: 'Menambahkan ruangan baru: ' . $request->nama_ruangan
        );

        return redirect()->back()->with('success', 'Ruangan berhasil ditambahkan!');
    }

    /**
     * Store multiple resources in storage.
     */
    public function storeMultiple(Request $request)
    {
        $request->validate([
            'ruangan' => 'required|array|min:1',
            'ruangan.*.nama_ruangan' => 'required|string|max:100',
        ]);

        foreach ($request->ruangan as $ruanganData) {
            Ruangan::create($ruanganData);
        }

        LogActivityService::log(
            action: 'CREATE_MULTIPLE',
            module: 'Ruangan',
            description: 'Menambahkan ' . count($request->ruangan) . ' data ruangan baru'
        );

        return redirect()->back()->with('success', count($request->ruangan) . ' data ruangan berhasil ditambahkan!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_ruangan' => 'required|string|max:100',
        ]);

        $ruangan = Ruangan::findOrFail($id);

        $oldData = $ruangan->toArray();
        $ruangan->update($request->all());

        LogActivityService::log(
            action: 'UPDATE',
            module: 'Ruangan',
            description: 'Memperbarui data ruangan dengan ID: ' . $id,
            oldData: $oldData,
            newData: $ruangan->toArray()
        );

        return redirect()->back()->with('success', 'Data ruangan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $ruangan = Ruangan::findOrFail($id);

        $oldData = $ruangan->toArray();

        // Cek apakah ruangan memiliki pasien
        if ($ruangan->pasien()->exists()) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus ruangan karena memiliki ' . $ruangan->pasien()->count() . ' pasien terkait!');
        }

        $ruangan->delete();

        LogActivityService::log(
            action: 'DELETE',
            module: 'Ruangan',
            description: 'Menghapus ruangan dengan ID: ' . $id,
            oldData: $oldData
        );

        return redirect()->back()->with('success', 'Ruangan berhasil dihapus!');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\DetailDataPemeriksaan;
use App\Models\DataPemeriksaan;
use Illuminate\Http\Request;

class DetailDataPemeriksaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');

        $details = DetailDataPemeriksaan::with('dataPemeriksaan.jenisPemeriksaan')
            ->when($search, function($query) use ($search) {
                return $query->whereHas('dataPemeriksaan', function($q) use ($search) {
                    $q->where('data_pemeriksaan', 'like', "%{$search}%")
                      ->orWhereHas('jenisPemeriksaan', function($q2) use ($search) {
                          $q2->where('nama_pemeriksaan', 'like', "%{$search}%");
                      });
                })
                ->orWhere('umur', 'like', "%{$search}%")
                ->orWhere('rujukan', 'like', "%{$search}%");
            })
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('detail-data-pemeriksaan.index', compact('details'));
    }

    /**
     * Get data pemeriksaan for Select2
     */
    public function getDataPemeriksaan(Request $request)
    {
        $search = $request->get('search');

        $dataPemeriksaan = DataPemeriksaan::with('jenisPemeriksaan')
            ->where(function ($query) use ($search) {
                $query->where('data_pemeriksaan', 'ILIKE', "%{$search}%")
                    ->orWhereHas('jenisPemeriksaan', function ($q) use ($search) {
                        $q->where('nama_pemeriksaan', 'ILIKE', "%{$search}%");
                    });
            })
            ->orderBy('data_pemeriksaan')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                return [
                    'id_data_pemeriksaan' => $item->id_data_pemeriksaan,
                    'nama_pemeriksaan'   => $item->data_pemeriksaan,
                    'jenis_pemeriksaan'  => $item->jenisPemeriksaan->nama_pemeriksaan ?? '-',
                ];
            });

        return response()->json($dataPemeriksaan);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $dataPemeriksaanList = DataPemeriksaan::with('jenisPemeriksaan')->get();
        return view('detail-data-pemeriksaan.create', compact('dataPemeriksaanList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_data_pemeriksaan' => 'required|exists:data_pemeriksaan,id_data_pemeriksaan',
            'urutan' => 'nullable|integer',
            'umur' => 'nullable|string|max:100',
            'jenis_kelamin' => 'nullable|string|max:20',
            'rujukan' => 'nullable|string|max:255',
            'satuan' => 'nullable|string|max:50',
            'metode' => 'nullable|string|max:100',
            'ch' => 'nullable|string|max:50',
            'cl' => 'nullable|string|max:50',
        ]);

        DetailDataPemeriksaan::create($request->all());

        return redirect()->route('detail-data-pemeriksaan.index')
            ->with('success', 'Data berhasil ditambahkan');
    }

    /**
     * Store multiple details
     */
    public function storeMultiple(Request $request)
    {
        $details = $request->input('details', []);

        foreach ($details as $detail) {
            $request->validate([
                "details.*.id_data_pemeriksaan" => 'required|exists:data_pemeriksaan,id_data_pemeriksaan',
                "details.*.urutan" => 'nullable|integer',
                "details.*.umur" => 'nullable|string|max:100',
                "details.*.jenis_kelamin" => 'nullable|string|max:20',
                "details.*.rujukan" => 'nullable|string|max:255',
                "details.*.satuan" => 'nullable|string|max:50',
                "details.*.metode" => 'nullable|string|max:100',
                "details.*.ch" => 'nullable|string|max:50',
                "details.*.cl" => 'nullable|string|max:50',
            ]);

            DetailDataPemeriksaan::create($detail);
        }

        return redirect()->route('detail-data-pemeriksaan.index')
            ->with('success', count($details) . ' data berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(DetailDataPemeriksaan $detailDataPemeriksaan)
    {
        return view('detail-data-pemeriksaan.show', compact('detailDataPemeriksaan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $detail = DetailDataPemeriksaan::findOrFail($id);
        $dataPemeriksaanList = DataPemeriksaan::with('jenisPemeriksaan')->get();

        return view('detail-data-pemeriksaan.edit', compact('detail', 'dataPemeriksaanList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $detail = DetailDataPemeriksaan::findOrFail($id);

        $request->validate([
            'id_data_pemeriksaan' => 'required|exists:data_pemeriksaan,id_data_pemeriksaan',
            'urutan' => 'nullable|integer',
            'umur' => 'nullable|string|max:100',
            'jenis_kelamin' => 'nullable|string|max:20',
            'rujukan' => 'nullable|string|max:255',
            'satuan' => 'nullable|string|max:50',
            'metode' => 'nullable|string|max:100',
            'ch' => 'nullable|string|max:50',
            'cl' => 'nullable|string|max:50',
        ]);

        $detail->update($request->all());

        return redirect()->route('detail-data-pemeriksaan.index')
            ->with('success', 'Data berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $detail = DetailDataPemeriksaan::findOrFail($id);
        $detail->delete();

        return redirect()->route('detail-data-pemeriksaan.index')
            ->with('success', 'Data berhasil dihapus');
    }

    /**
     * Destroy multiple resources
     */
    public function destroyMultiple(Request $request)
    {
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return redirect()->back()->with('error', 'Tidak ada data yang dipilih');
        }

        $deleted = DetailDataPemeriksaan::whereIn('id_detail_data_pemeriksaan', $ids)->delete();

        return redirect()->route('detail-data-pemeriksaan.index')
            ->with('success', $deleted . ' data berhasil dihapus');
    }
}

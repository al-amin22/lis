<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pasien;
use App\Models\PemeriksaanHematology;
use App\Models\PemeriksaanKimia;

class UjiLabController extends Controller
{
    public function index(Request $request)
    {
        $rmPasien = $request->input('rm_pasien');
        $pasien = null;
        $hematology = collect();
        $kimia = collect();

        if ($rmPasien) {
            // Ambil data pasien
            $pasien = Pasien::where('rm_pasien', $rmPasien)->first();

            if ($pasien) {
                // Ambil semua data pemeriksaan pasien
                $hematology = PemeriksaanHematology::where('rm_pasien', $pasien->rm_pasien)
                    ->orderBy('id', 'asc')
                    ->get();

                $kimia = PemeriksaanKimia::where('rm_pasien', $pasien->rm_pasien)
                    ->orderBy('id', 'asc')
                    ->get();
            }
        }

        return view('result', compact('pasien', 'hematology', 'kimia', 'rmPasien'));
    }
}

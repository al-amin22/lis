<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pasien;
use App\Models\Ruangan;
use App\Models\Kelas;
use App\Models\Dokter;
use App\Models\Pemeriksa;
use PhpOffice\PhpWord\TemplateProcessor;
use App\Models\PemeriksaanKimia;
use App\Models\PemeriksaanHematology;
use Carbon\Carbon;
use App\Jobs\GeneratePdfJob;
use Illuminate\Support\Facades\DB;
use App\Services\LogActivityService;

class UserController extends Controller
{
    public function index()
    {
        $pasiens = Pasien::with('hematology', 'kimia')
            ->orderBy('updated_at', 'desc') // urutkan terbaru dulu
            ->paginate(10);
        $statusSelesai = //total pasien jika rm_pasien ada di tabel salah satu hematology/kimia, maka status selesai
            Pasien::whereHas('hematology')
            ->orWhereHas('kimia')
            ->count();
        $statusProses = //total jika rm_pasien tidak ada di tabel hematology/kimia, maka status proses
            Pasien::whereDoesntHave('hematology')
            ->whereDoesntHave('kimia')
            ->count();
        $statusOrders = Pasien::count(); //total semua pasien

        LogActivityService::log(
            action: 'READ',
            module: 'Dashboard Pasien',
            description: 'Melihat daftar pasien dan status pemeriksaan'
        );

        return view('user.index', compact('pasiens', 'statusSelesai', 'statusProses', 'statusOrders'));
    }

    public function search(Request $request)
    {
        $query = Pasien::with('hematology', 'kimia');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('rm_pasien', 'ilike', "%{$search}%")
                    ->orWhere('nama_pasien', 'ilike', "%{$search}%")
                    ->orWhere('no_lab', 'ilike', "%{$search}%")
                    ->orWhere('nota', 'ilike', "%{$search}%");
            });
        }

        $pasiens = $query->paginate(10)->appends($request->all());

        return view('user.index', compact('pasiens'));
    }

    public function show($no_lab)
    {
        // Ambil pasien + relasi
        $pasien = Pasien::with('hematology', 'kimia')->findOrFail($no_lab);

        // Ambil data hematology berdasarkan ID yang urut
        $hematology = $pasien->hematology()->orderBy('id_pemeriksaan_hematology', 'asc')->get();

        // Khusus hematology: ambil hanya 18 jenis pemeriksaan yang Anda inginkan
        $jenis_pemeriksaan = [
            'WBC',
            'Neutrofil%',
            'Limfosit%',
            'Monosit%',
            'Eosinofil%',
            'Basofil%',
            'RBC',
            'HGB',
            'HCT',
            'MCV',
            'MCH',
            'MCHC',
            'RDW-CV',
            'RDW-SD',
            'PLT',
            'MPV',
            'PDW',
            'PCT'
        ];

        $hematology_fix = [];

        foreach ($jenis_pemeriksaan as $jenis) {

            // Cari yang EXACT MATCH dulu
            $row = $hematology->first(
                fn($item) => strtolower(trim($item->jenis_pengujian)) === strtolower(trim($jenis))
            );

            // Kalau tidak ketemu, coba LIKE
            if (!$row) {
                $row = $hematology->first(
                    fn($item) => stripos($item->jenis_pengujian, $jenis) !== false
                );
            }

            // Kalau masih tidak ketemu → isi null / default
            $hematology_fix[] = $row ?? null;
        }

        // Ambil kimia sesuai kebutuhan
        $kimia = $pasien->kimia()->orderBy('id_pemeriksaan_kimia', 'asc')->get();

        return view('user.detail', [
            'pasien' => $pasien,
            'hematology_fix' => $hematology_fix,
            'kimia' => $kimia
        ]);
    }

    public function history($rm_pasien = null)
    {
        try {
            // Jika RM pasien null, siapkan data kosong
            if (is_null($rm_pasien) || empty($rm_pasien)) {
                $histories = collect(); // koleksi kosong
                $latestPatient = null;

                return view('history', compact('histories', 'latestPatient'));
            }

            // Ambil semua data pasien dengan RM yang sama
            $histories = Pasien::where('rm_pasien', $rm_pasien)
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            // Ambil data pasien terbaru untuk header
            $latestPatient = Pasien::where('rm_pasien', $rm_pasien)
                ->orderBy('created_at', 'desc')
                ->first();

            // Jika tidak ada data pasien dengan RM tersebut
            if (!$latestPatient) {
                $histories = collect(); // tetap bisa akses halaman tapi tanpa data
            }

            return view('user.history', compact('histories', 'latestPatient'));
        } catch (\Exception $e) {
            return redirect()->route('pasien.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function printPdf($no_lab)
    {
        // Jalankan job di background
        GeneratePdfJob::dispatch($no_lab);

        // Tampilkan halaman loader
        return view('print', [
            'no_lab' => $no_lab
        ]);
    }

    public function checkFile($no_lab)
    {
        // Selalu jalankan job setiap panggilan
        GeneratePdfJob::dispatch($no_lab);
        sleep(5);

        $folder = public_path("file");
        $pattern = "{$folder}/hasil_pengujian_{$no_lab}*.pdf";

        $files = glob($pattern);

        if (empty($files)) {
            return response()->json(['ready' => false]);
        }

        // Ambil file terbaru berdasarkan suffix terbesar
        $latest = collect($files)->sortByDesc(function ($file) {

            $name = pathinfo($file, PATHINFO_FILENAME);

            if (preg_match('/_(\d+)$/', $name, $m)) {
                return intval($m[1]);
            }

            return 0; // tanpa suffix dianggap paling lama
        })->first();

        return response()->json([
            'ready' => true,
            'file'  => asset("file/" . basename($latest))
        ]);
    }
}

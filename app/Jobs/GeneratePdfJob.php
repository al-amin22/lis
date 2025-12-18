<?php

namespace App\Jobs;

use Carbon\Carbon;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Pasien;

// Untuk Endroid QRCode versi 4.x
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Writer\PngWriter;

class GeneratePdfJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $no_lab;

    public function __construct($no_lab)
    {
        $this->no_lab = $no_lab;
    }

    public function handle()
    {
        // ==========================
        // SET LOCALE DAN TIMEZONE
        // ==========================
        Carbon::setLocale('id');  // Bahasa Indonesia

        // ==========================
        // AMBIL DATA PASIEN + RELASI
        // ==========================
        $pasien = Pasien::with(['hematology', 'kimia', 'dokter', 'pemeriksa'])
            ->where('no_lab', $this->no_lab)
            ->firstOrFail();

        $hematologi = $pasien->hematology;
        $kimia = $pasien->kimia;

        // ==========================
        // PILIH TEMPLATE SESUAI DATA
        // ==========================
        if ($hematologi->count() > 0 && $kimia->count() > 0) {
            $templatePath = public_path('template/print.docx');
        } elseif ($hematologi->count() > 0) {
            $templatePath = public_path('template/hema.docx');
        } elseif ($kimia->count() > 0) {
            $templatePath = public_path('template/kimia.docx');
        } else {
            return; // Tidak ada data
        }

        $template = new TemplateProcessor($templatePath);

        // ==========================
        // DATA PASIEN
        // ==========================
        $template->setValue('nama_pasien', $pasien->nama_pasien ?? '');
        $template->setValue('jenis_kelamin', $pasien->jenis_kelamin ?? '');
        $template->setValue('no_lab', $pasien->no_lab ?? '');
        $template->setValue('rm_pasien', $pasien->rm_pasien ?? '');
        $template->setValue('tgl_lahir', $pasien->tgl_lahir ?? '');
        $template->setValue('umur', $pasien->umur ?? '');

        // Waktu periksa & validasi pakai timezone Jakarta
        $template->setValue('waktu_periksa', $pasien->updated_at ?? '');

        $template->setValue('alamat', $pasien->alamat ?? '');

        $now = Carbon::now('Asia/Jakarta');
        $template->setValue('waktu_validasi', $now->format('Y-m-d H:i:s'));

        $template->setValue('dokter', $pasien->pengirim ?? '');
        $template->setValue('analis', $pasien->dokter->nama_dokter ?? '');
        $template->setValue('asal_kunjungan', $pasien->ket_klinik ?? '');
        $template->setValue('penjamin', $pasien->nota ?? '');

        // Tanggal sekarang & waktu sekarang pakai Jakarta

        $template->setValue('today', $now->translatedFormat('d F Y')); // nama bulan bahasa Indonesia
        $template->setValue('waktu_sekarang', $now->format('Y-m-d H:i:s'));

        // ==========================
        // HEMATOLOGI
        // ==========================
        if ($hematologi->count() > 0) {
            $rows = [];
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

            foreach ($jenis_pemeriksaan as $jenis) {
                $row_data = $hematologi->first(fn($item) => strtolower(trim($item->jenis_pengujian)) === strtolower(trim($jenis)));
                if (!$row_data) $row_data = $hematologi->firstWhere('jenis_pengujian', 'LIKE', "%$jenis%");

                $hasil = $row_data->hasil_pengujian ?? '';
                $rujukan = $row_data->rujukan ?? '';
                $satuan = $row_data->satuan_hasil_pengujian ?? '';
                $keterangan = strtoupper(trim($row_data->keterangan ?? ''));
                $a = $b = $c = "";
                if ($keterangan === "H") $c = "H";
                elseif ($keterangan === "L") $b = "L";

                $rows[] = [
                    "jenis_pemeriksaan" => $jenis,
                    "hasil" => $hasil,
                    "nilai_rujukan" => $rujukan,
                    "satuan" => $satuan,
                    "a" => $a,
                    "b" => $b,
                    "c" => $c
                ];
            }

            $template->cloneBlock('hematologi', 0, true, false, $rows, true);
        } else {
            $template->deleteBlock('hematologi');
        }

        // ==========================
        // KIMIA
        // ==========================
        if ($kimia->count() == 0) {
            $template->deleteBlock('kimia');
        } else {
            $rows = [];
            foreach ($kimia as $row) {
                $ketNormal = $ketLow = $ketHigh = "";
                if ($row->keterangan === "H") $ketHigh = "H";
                elseif ($row->keterangan === "L") $ketLow = "L";
                $rows[] = [
                    "analysis" => $row->analysis ?? "",
                    "hasil_pengujian" => $row->hasil_pengujian ?? "",
                    "rujukan" => $row->rujukan ?? "",
                    "satuan" => $row->satuan_hasil_pengujian ?? "",
                    "a" => $ketNormal,
                    "b" => $ketLow,
                    "c" => $ketHigh
                ];
            }
            $template->cloneBlock('kimia', 0, true, false, $rows, true);
        }

        // ==========================
        // QR CODE
        // ==========================
        $todayFile = $now->format('Y-m-d');
        $qrDir = public_path('file/qr');
        if (!is_dir($qrDir)) mkdir($qrDir, 0755, true);

        $qrPath = "$qrDir/qr_$todayFile.png";

        if (!file_exists($qrPath)) {
            $qrText = "dr. DONNY KOSTRADI, M.Kes, Sp.PK\nMR15712507005085\nHasil Pemeriksaan Laboratorium RS. Baiturrahim\nJambi, " . $now->translatedFormat('d F Y');

            $result = Builder::create()
                ->writer(new PngWriter())
                ->data($qrText)
                ->encoding(new Encoding('UTF-8'))
                ->size(200)
                ->margin(5)
                ->build();

            $result->saveToFile($qrPath);
        }

        $template->setImageValue('qr_code', [
            'path' => $qrPath,
        ]);

        // ==========================
        // SAVE DOCX & GENERATE PDF
        // ==========================

        $folder = public_path("file");
        $base   = "hasil_pengujian_{$this->no_lab}";

        // Cari nomor file berikutnya
        $counter = 0;
        $finalDocx = "{$folder}/{$base}.docx";

        while (file_exists($finalDocx)) {
            $counter++;
            $finalDocx = "{$folder}/{$base}_{$counter}.docx";
        }

        // Simpan DOCX baru
        $template->saveAs($finalDocx);


        // ==========================
        // KONVERSI DOCX -> PDF via LibreOffice
        // ==========================

        $soffice = "\"C:\\Program Files\\LibreOffice\\program\\soffice.exe\"";

        $docxQuoted   = '"' . $finalDocx . '"';
        $outDirQuoted = '"' . $folder . '"';

        $command = "{$soffice} --headless --norestore --convert-to pdf {$docxQuoted} --outdir {$outDirQuoted}";

        // Eksekusi LibreOffice
        exec($command);
    }
}

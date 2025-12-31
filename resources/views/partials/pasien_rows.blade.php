@forelse($pasiens as $patient)
<tr>
    <td>
        @php
            $nomorRegistrasi = $patient->nomor_registrasi ?? '';
            $tanggalFormat = '-';

            if (strlen($nomorRegistrasi) >= 6) {
                $datePart = substr($nomorRegistrasi, 0, 6);
                $year  = substr($datePart, 0, 2);
                $month = substr($datePart, 2, 2);
                $day   = substr($datePart, 4, 2);
                $year = (int)$year < 50 ? '20' . $year : '19' . $year;
                $tanggalFormat = "{$day}/{$month}/{$year}";
            }
            echo $tanggalFormat;
        @endphp
    </td>
    <td>{{ $patient->nomor_registrasi ?? '-'}}</td>
    <td>{{ $patient->rm_pasien ?? '-'}}</td>
    <td>{{ $patient->nama_pasien ?? '-'}}</td>
    <td>{{ $patient->ket_klinik ?? '-'}}</td>
    <td>{{ $patient->nota ?? '-'}}</td>
    <td>
        @if($patient->id_pemeriksa && $patient->waktu_validasi)
            <span class="badge bg-success">Selesai</span>
        @else
            <span class="badge bg-warning">Diproses</span>
        @endif
    </td>
    <td>
        <a href="{{ route('pasien.print', $patient->no_lab) }}" target="_blank" class="btn btn-sm btn-secondary">
            Print
        </a>
        <a href="{{ route('pasien.show', $patient->no_lab) }}" class="btn btn-sm btn-primary">View</a>
        <a href="{{ route('pasien.history', $patient->rm_pasien ?? '') }}"
        class="btn btn-sm btn-info" title="History">
            History
        </a>
        <form action="{{ route('pasien.destroy', $patient->no_lab) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                Delete
            </button>
        </form>
    </td>
</tr>
@empty
<tr>
    <td colspan="8" class="text-center">Tidak ada data pasien</td>
</tr>
@endforelse

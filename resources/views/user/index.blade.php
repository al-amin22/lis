@extends('layouts_user.app')

@section('content')
<!-- App hero header starts -->
<div class="app-hero-header d-flex align-items-center">
    <!-- Breadcrumb starts -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <i class="ri-home-8-line lh-1 pe-3 me-3 border-end"></i>
            <a href="{{ url('user/dashboard') }}">Home</a>
        </li>
        <li class="breadcrumb-item text-primary" aria-current="page">
            Dashboard
        </li>
    </ol>
    <!-- Breadcrumb ends -->

    <!-- Sales stats starts -->
    <!-- <div class="ms-auto d-lg-flex d-none flex-row">
        <div class="d-flex flex-row gap-1 day-sorting">
            <button class="btn btn-sm btn-primary">Today</button>
            <button class="btn btn-sm">7d</button>
            <button class="btn btn-sm">2w</button>
            <button class="btn btn-sm">1m</button>
            <button class="btn btn-sm">3m</button>
            <button class="btn btn-sm">6m</button>
            <button class="btn btn-sm">1y</button>
        </div>
    </div> -->
    <!-- Sales stats ends -->
</div>
<!-- App Hero header ends -->

<!-- App body starts -->
<div class="app-body">
    <!-- Row starts -->
    <div class="row gx-3">
        <div class="col-xxl-12 col-sm-12">
            <div class="card mb-3 bg-2">
                <div class="card-body">
                    <div class="py-4 px-3 text-white">
                        <h6>Selamat Datang kembali Di</h6>
                        <h2>ARVINDO LIS</h2>
                        <h5>
                            @if($activeDate->isToday())
                                Pasien Hari Ini :
                            @else
                                Pasien Tanggal {{ $activeDate->translatedFormat('d F Y') }} :
                            @endif
                        </h5>

                        <div class="mt-4 d-flex gap-3">
                            <div class="d-flex align-items-center">
                                <div class="icon-box lg bg-arctic rounded-3 me-3">
                                    <i class="ri-surgical-mask-line fs-4"></i>
                                </div>
                                <div class="d-flex flex-column">
                                    <h2 class="m-0 lh-1">{{ $statusOrders ?? 0}}</h2>
                                    <p class="m-0">Total Pasien</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="icon-box lg bg-lime rounded-3 me-3">
                                    <i class="ri-lungs-line fs-4"></i>
                                </div>
                                <div class="d-flex flex-column">
                                    <h2 class="m-0 lh-1">{{ $statusSelesai ?? 0}}</h2>
                                    <p class="m-0">Selesai</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="icon-box lg bg-peach rounded-3 me-3">
                                    <i class="ri-walk-line fs-4"></i>
                                </div>
                                <div class="d-flex flex-column">
                                    <h2 class="m-0 lh-1">{{ $statusProses ?? 0}}</h2>
                                    <p class="m-0">Diproses</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Alert pesan berhasil/gagal --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    {{-- Tampilkan error validasi --}}
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- App body ends -->
    <div class="row gx-3">
        <div class="col-sm-12">
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Pemeriksaan
                        @if(request('search_date'))
                            Tanggal: {{ \Carbon\Carbon::parse(request('search_date'))->format('d/m/Y') }}
                        @else
                            Hari Ini
                        @endif
                    </h5>
                    <div class="d-flex align-items-center gap-2">
                        <!-- <button id="ambilOrder" class="btn btn-sm btn-primary">Cek Order</button> -->
                        <a href="{{ route('user.index') }}" class="btn btn-sm btn-primary">Refresh</a>

                        <!-- FORM PENCARIAN TANGGAL -->
                        <div class="position-relative" style="max-width: 250px;">
                            <form method="GET" action="{{ route('user.search') }}" class="d-flex" id="dateSearchForm">
                                <input type="date"
                                    name="search_date"
                                    id="searchDateInput"
                                    class="form-control form-control-sm me-2"
                                    value="{{ request('search_date') }}"
                                    title="Cari berdasarkan tanggal">
                                <button type="submit" class="btn btn-sm btn-primary" title="Cari berdasarkan tanggal">
                                    <i class="ri-calendar-line"></i>
                                </button>
                            </form>
                            <div id="dateSearchLoading" class="position-absolute top-50 end-0 translate-middle-y me-2" style="display: none;">
                                <div class="spinner-border spinner-border-sm text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>

                        <!-- FORM PENCARIAN GLOBAL -->
                        <form method="GET" action="{{ route('user.search') }}" class="d-flex" style="max-width: 250px;">
                            <input type="text"
                                name="search"
                                class="form-control form-control-sm me-2"
                                placeholder="Cari Data Pasien..."
                                value="{{ request('search') }}">

                            {{-- 🔥 PERTAHANKAN TANGGAL --}}
                            @if(request('search_date'))
                                <input type="hidden" name="search_date" value="{{ request('search_date') }}">
                            @endif

                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="ri-search-line"></i>
                            </button>
                        </form>

                        <!-- TOMBOL RESET JIKA ADA FILTER -->
                        @if(request('search_date') || request('search'))
                            <a href="{{ route('user.index') }}" class="btn btn-sm btn-secondary" title="Reset filter">
                                <i class="ri-close-line"></i> Reset
                            </a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <!-- Table starts -->
                    <div class="table-outer">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle text-nowrap">
                                <!-- GANTI BAGIAN HEADER TABLE -->
                                <thead>
                                    <tr>
                                        <th>
                                            Tanggal
                                            <div style="margin-top: 5px;">
                                                <form method="GET" action="{{ route('user.index') }}" class="filter-form">
                                                    <input type="hidden" name="search_date" value="{{ request('search_date') }}">
                                                    <input type="text"
                                                        name="filter_tanggal"
                                                        class="form-control form-control-sm filter-input"
                                                        placeholder="Filter tanggal..."
                                                        value="{{ request('filter_tanggal') }}"
                                                        style="min-width: 80px;"
                                                        onchange="this.form.submit()">
                                                </form>
                                            </div>
                                        </th>
                                        <th>
                                            No. Reg Lab
                                            <div style="margin-top: 5px;">
                                                <form method="GET" action="{{ route('user.index') }}" class="filter-form">
                                                    <input type="hidden" name="search_date" value="{{ request('search_date') }}">
                                                    @if(request('filter_tanggal'))
                                                        <input type="hidden" name="filter_tanggal" value="{{ request('filter_tanggal') }}">
                                                    @endif
                                                    <input type="text"
                                                        name="filter_registrasi"
                                                        class="form-control form-control-sm filter-input"
                                                        placeholder="Filter no. registrasi..."
                                                        value="{{ request('filter_registrasi') }}"
                                                        style="min-width: 50px;"
                                                        onchange="this.form.submit()">
                                                </form>
                                            </div>
                                        </th>
                                        <th>
                                            RM Pasien
                                            <div style="margin-top: 5px;">
                                                <form method="GET" action="{{ route('user.index') }}" class="filter-form">
                                                    <input type="hidden" name="search_date" value="{{ request('search_date') }}">
                                                    @if(request('filter_tanggal'))
                                                        <input type="hidden" name="filter_tanggal" value="{{ request('filter_tanggal') }}">
                                                    @endif
                                                    @if(request('filter_registrasi'))
                                                        <input type="hidden" name="filter_registrasi" value="{{ request('filter_registrasi') }}">
                                                    @endif
                                                    <input type="text"
                                                        name="filter_rm"
                                                        class="form-control form-control-sm filter-input"
                                                        placeholder="Filter RM..."
                                                        value="{{ request('filter_rm') }}"
                                                        style="min-width: 70px;"
                                                        onchange="this.form.submit()">
                                                </form>
                                            </div>
                                        </th>
                                        <th>
                                            Nama Pasien
                                            <div style="margin-top: 5px;">
                                                <form method="GET" action="{{ route('user.index') }}" class="filter-form">
                                                    <input type="hidden" name="search_date" value="{{ request('search_date') }}">
                                                    @if(request('filter_tanggal'))
                                                        <input type="hidden" name="filter_tanggal" value="{{ request('filter_tanggal') }}">
                                                    @endif
                                                    @if(request('filter_registrasi'))
                                                        <input type="hidden" name="filter_registrasi" value="{{ request('filter_registrasi') }}">
                                                    @endif
                                                    @if(request('filter_rm'))
                                                        <input type="hidden" name="filter_rm" value="{{ request('filter_rm') }}">
                                                    @endif
                                                    <input type="text"
                                                        name="filter_nama"
                                                        class="form-control form-control-sm filter-input"
                                                        placeholder="Filter nama..."
                                                        value="{{ request('filter_nama') }}"
                                                        style="min-width: 180px;"
                                                        onchange="this.form.submit()">
                                                </form>
                                            </div>
                                        </th>
                                        <th>
                                            Asal Kunjungan
                                            <div style="margin-top: 5px;">
                                                <form method="GET" action="{{ route('user.index') }}" class="filter-form">
                                                    <input type="hidden" name="search_date" value="{{ request('search_date') }}">
                                                    @foreach(['filter_tanggal', 'filter_registrasi', 'filter_rm', 'filter_nama'] as $filter)
                                                        @if(request($filter))
                                                            <input type="hidden" name="{{ $filter }}" value="{{ request($filter) }}">
                                                        @endif
                                                    @endforeach
                                                    <input type="text"
                                                        name="filter_asal"
                                                        class="form-control form-control-sm filter-input"
                                                        placeholder="Filter asal..."
                                                        value="{{ request('filter_asal') }}"
                                                        style="min-width: 140px;"
                                                        onchange="this.form.submit()">
                                                </form>
                                            </div>
                                        </th>
                                        <th>
                                            Penjamin
                                            <div style="margin-top: 5px;">
                                                <form method="GET" action="{{ route('user.index') }}" class="filter-form">
                                                    <input type="hidden" name="search_date" value="{{ request('search_date') }}">
                                                    @foreach(['filter_tanggal', 'filter_registrasi', 'filter_rm', 'filter_nama', 'filter_asal'] as $filter)
                                                        @if(request($filter))
                                                            <input type="hidden" name="{{ $filter }}" value="{{ request($filter) }}">
                                                        @endif
                                                    @endforeach
                                                    <input type="text"
                                                        name="filter_penjamin"
                                                        class="form-control form-control-sm filter-input"
                                                        placeholder="Filter penjamin..."
                                                        value="{{ request('filter_penjamin') }}"
                                                        style="min-width: 120px;"
                                                        onchange="this.form.submit()">
                                                </form>
                                            </div>
                                        </th>
                                        <th>
                                            Status
                                            <div style="margin-top: 5px;">
                                                <form method="GET" action="{{ route('user.index') }}" class="filter-form">
                                                    <input type="hidden" name="search_date" value="{{ request('search_date') }}">
                                                    @foreach(['filter_tanggal', 'filter_registrasi', 'filter_rm', 'filter_nama', 'filter_asal', 'filter_penjamin'] as $filter)
                                                        @if(request($filter))
                                                            <input type="hidden" name="{{ $filter }}" value="{{ request($filter) }}">
                                                        @endif
                                                    @endforeach
                                                    <select name="filter_status"
                                                            class="form-control form-control-sm filter-input"
                                                            style="min-width: 80px;"
                                                            onchange="this.form.submit()">
                                                        <option value="">Semua Status</option>
                                                        <option value="Selesai" {{ request('filter_status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                                        <option value="Diproses" {{ request('filter_status') == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                                                    </select>
                                                </form>
                                            </div>
                                        </th>
                                        <th>
                                            Actions
                                            <div style="margin-top: 5px;">
                                                <a href="{{ route('user.index') }}"
                                                class="btn btn-sm btn-outline-secondary w-100">
                                                    Reset Filter
                                                </a>
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pasiens as $patient)
                                    <tr>
                                        <td>
                                            @php
                                                // Parse tanggal dari nomor_registrasi (yymmdd)
                                                $nomorRegistrasi = $patient->nomor_registrasi ?? '';

                                                if (strlen($nomorRegistrasi) >= 6) {
                                                    $datePart = substr($nomorRegistrasi, 0, 6);

                                                    $year  = substr($datePart, 0, 2);
                                                    $month = substr($datePart, 2, 2);
                                                    $day   = substr($datePart, 4, 2);

                                                    // Konversi tahun 2 digit ke 4 digit
                                                    $year = (int)$year < 50 ? '20' . $year : '19' . $year;

                                                    echo "{$day}/{$month}/{$year}";
                                                } else {
                                                    echo '-';
                                                }
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
                                            @if (!empty($patient->id_pemeriksa) && !empty($patient->waktu_validasi))
                                                <a href="{{ route('user.print', $patient->no_lab) }}"
                                                target="_blank"
                                                class="btn btn-sm btn-secondary">
                                                    Print
                                                </a>
                                                <a href="{{ route('user.history', $patient->rm_pasien ?? '') }}"
                                                class="btn btn-sm btn-info" title="History">
                                                    History
                                                </a>
                                            @else
                                                <span class="badge bg-warning text-dark">
                                                    Belum divalidasi
                                                </span>
                                            @endif


                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-center mt-3">
                                {{ $pasiens->links('pagination::bootstrap-5') }}
                            </div>

                        </div>
                    </div>
                    <!-- Table ends -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    async function ambilOrder(auto = false) {
        const btn = document.getElementById('ambilOrder');

        try {
            btn.disabled = true;
            btn.innerText = auto ? 'Proses... ⏳' : 'Proses... ⏳';

            const response = await fetch('/api/pasien/ambil-order', {
                method: 'POST',
                headers: {'Accept': 'application/json'}
            });

            const result = await response.json();

            if (result.success) {
                btn.innerText = `Berhasil..`;
                setTimeout(() => btn.innerText = 'Ambil Order', 5000);

                // 🔥 RELOAD HALAMAN AGAR DATA LANGSUNG MUNCUL
                location.reload();
            } else {
                btn.innerText = 'Gagal ❌';
                setTimeout(() => btn.innerText = 'Ambil Order', 5000);
            }

        } catch (error) {
            if (!auto) alert('Error: ' + error.message);
            btn.innerText = 'Error ⚠️';
            setTimeout(() => btn.innerText = 'Ambil Order', 3000);
        } finally {
            btn.disabled = false;
        }
    }

    // Klik manual
    document.getElementById('ambilOrder').addEventListener('click', () => ambilOrder(false));

    // Otomatis setiap 20 menit
    setInterval(() => ambilOrder(true), 20 * 60 * 1000);
</script>

<script>
    // Auto-submit dengan loading indicator
    document.getElementById('searchDateInput').addEventListener('change', function() {
        const loading = document.getElementById('dateSearchLoading');
        const form = this.form;

        // Tampilkan loading
        if (loading) loading.style.display = 'block';

        // Submit form
        setTimeout(() => {
            form.submit();
        }, 100);
    });

    document.addEventListener('DOMContentLoaded', function() {
        // Sembunyikan loading saat halaman selesai dimuat
        const loading = document.getElementById('dateSearchLoading');
        if (loading) loading.style.display = 'none';

        // Set nilai input tanggal dari URL
        const urlParams = new URLSearchParams(window.location.search);
        const searchDate = urlParams.get('search_date');
        const searchInput = document.getElementById('searchDateInput');

        if (searchDate && searchInput) {
            searchInput.value = searchDate;
        }

        // ==================== FILTER KOLOM REAL-TIME ====================

        // Variabel untuk menyimpan semua data
        let allPatientsData = [];

        // Kumpulkan semua data dari tabel
        function initializeAllData() {
            const rows = document.querySelectorAll('tbody tr');
            allPatientsData = [];

            rows.forEach((row, index) => {
                if (row.cells.length >= 7) { // Pastikan ini baris data, bukan baris kosong
                    const cells = row.cells;
                    allPatientsData.push({
                        element: row,
                        tanggal: cells[0].textContent.trim().toLowerCase(),
                        registrasi: cells[1].textContent.trim().toLowerCase(),
                        rm: cells[2].textContent.trim().toLowerCase(),
                        nama: cells[3].textContent.trim().toLowerCase(),
                        asal: cells[4].textContent.trim().toLowerCase(),
                        penjamin: cells[5].textContent.trim().toLowerCase(),
                        status: cells[6].textContent.includes('Selesai') ? 'selesai' : 'diproses',
                        originalIndex: index
                    });
                }
            });
        }

        // Panggil saat pertama kali load
        initializeAllData();

        // Fungsi untuk menerapkan filter
        function applyFilters() {
            const filters = {};

            // Kumpulkan semua nilai filter
            document.querySelectorAll('.filter-input').forEach(input => {
                const column = input.getAttribute('data-column');
                if (input.tagName === 'SELECT') {
                    filters[column] = input.value.toLowerCase();
                } else {
                    filters[column] = input.value.trim().toLowerCase();
                }
            });

            // Hitung berapa baris yang ditampilkan
            let visibleCount = 0;

            // Filter setiap baris
            allPatientsData.forEach(patient => {
                let shouldShow = true;

                // Cek setiap kolom
                for (const [columnIndex, filterValue] of Object.entries(filters)) {
                    if (filterValue === '') continue;

                    let cellValue;
                    switch(columnIndex) {
                        case '0': cellValue = patient.tanggal; break;
                        case '1': cellValue = patient.registrasi; break;
                        case '2': cellValue = patient.rm; break;
                        case '3': cellValue = patient.nama; break;
                        case '4': cellValue = patient.asal; break;
                        case '5': cellValue = patient.penjamin; break;
                        case '6': cellValue = patient.status; break;
                        default: cellValue = '';
                    }

                    // Untuk status, perlu penanganan khusus
                    if (columnIndex === '6') {
                        if (filterValue !== 'semua' && filterValue !== '' &&
                            !cellValue.includes(filterValue)) {
                            shouldShow = false;
                            break;
                        }
                    }
                    // Untuk kolom lainnya, cek apakah mengandung teks filter
                    else if (!cellValue.includes(filterValue)) {
                        shouldShow = false;
                        break;
                    }
                }

                // Tampilkan/sembunyikan baris
                if (shouldShow) {
                    patient.element.style.display = '';
                    visibleCount++;
                } else {
                    patient.element.style.display = 'none';
                }
            });

            // Tampilkan pesan jika tidak ada data
            const tbody = document.querySelector('tbody');
            const noDataRow = tbody.querySelector('tr.no-data-message');

            if (visibleCount === 0) {
                if (!noDataRow) {
                    const newRow = document.createElement('tr');
                    newRow.className = 'no-data-message';
                    newRow.innerHTML = `
                        <td colspan="8" class="text-center py-4">
                            <div class="text-muted">
                                <i class="ri-search-line fs-3"></i>
                                <p class="mt-2 mb-0">Tidak ada data yang sesuai dengan filter</p>
                            </div>
                        </td>
                    `;
                    tbody.appendChild(newRow);
                }
            } else if (noDataRow) {
                noDataRow.remove();
            }

            // Update counter (jika ada elemen untuk counter)
            updateCounter(visibleCount);
        }

        // Fungsi untuk update counter
        function updateCounter(visibleCount) {
            const counterElement = document.getElementById('filterCounter');
            if (!counterElement) {
                // Buat elemen counter jika belum ada
                const cardHeader = document.querySelector('.card-header');
                const counter = document.createElement('span');
                counter.id = 'filterCounter';
                counter.className = 'badge bg-info ms-2';
                counter.textContent = `${visibleCount} data`;
                cardHeader.appendChild(counter);
            } else {
                counterElement.textContent = `${visibleCount} data`;
            }
        }

        // Event listener untuk input filter
        document.querySelectorAll('.filter-input').forEach(input => {
            input.addEventListener('input', function() {
                clearTimeout(this.timeout);
                this.timeout = setTimeout(() => {
                    applyFilters();
                }, 300);
            });

            // Untuk select box
            input.addEventListener('change', function() {
                applyFilters();
            });
        });

        // Reset semua filter
        document.getElementById('resetFilters').addEventListener('click', function() {
            document.querySelectorAll('.filter-input').forEach(input => {
                if (input.tagName === 'SELECT') {
                    input.value = '';
                } else {
                    input.value = '';
                }
            });
            applyFilters();
        });

        // Terapkan filter saat pertama kali load (jika ada filter dari URL)
        setTimeout(() => {
            applyFilters();
        }, 100);
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let debounceTimer;

        // Debounce untuk input text
        document.querySelectorAll('.filter-input').forEach(input => {
            if (input.tagName !== 'SELECT') {
                input.addEventListener('input', function() {
                    clearTimeout(debounceTimer);

                    // Tampilkan loading
                    const loading = document.getElementById('filterLoading');
                    if (loading) loading.style.display = 'block';

                    debounceTimer = setTimeout(() => {
                        this.form.submit();
                    }, 500);
                });
            }
        });

        // Untuk select langsung submit
        document.querySelectorAll('select.filter-input').forEach(select => {
            select.addEventListener('change', function() {
                const loading = document.getElementById('filterLoading');
                if (loading) loading.style.display = 'block';
                this.form.submit();
            });
        });
    });
</script>

<script>
    async function applyFilters() {
        const filters = {
            search_date: document.querySelector('[name="search_date"]')?.value || '',
            filter_tanggal: document.querySelector('[name="filter_tanggal"]')?.value || '',
            filter_registrasi: document.querySelector('[name="filter_registrasi"]')?.value || '',
            filter_rm: document.querySelector('[name="filter_rm"]')?.value || '',
            filter_nama: document.querySelector('[name="filter_nama"]')?.value || '',
            filter_asal: document.querySelector('[name="filter_asal"]')?.value || '',
            filter_penjamin: document.querySelector('[name="filter_penjamin"]')?.value || '',
            filter_status: document.querySelector('[name="filter_status"]')?.value || '',
            search: document.querySelector('[name="search"]')?.value || '',
            page: 1 // Reset ke halaman 1 saat filter berubah
        };

        // Hapus filter kosong
        Object.keys(filters).forEach(key => {
            if (!filters[key]) delete filters[key];
        });

        const queryString = new URLSearchParams(filters).toString();
        const url = `{{ route('user.index') }}?${queryString}`;

        try {
            // Tampilkan loading
            document.getElementById('tableLoading').style.display = 'block';

            const response = await fetch(url);
            const html = await response.text();

            // Parse HTML dan update hanya bagian table
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newTable = doc.querySelector('.table-responsive');

            document.querySelector('.table-responsive').innerHTML = newTable.innerHTML;

            // Update pagination
            const newPagination = doc.querySelector('.pagination');
            if (newPagination) {
                document.querySelector('.pagination').innerHTML = newPagination.innerHTML;
            }

            // Update counter
            updateFilterCounter(filters);

        } catch (error) {
            console.error('Error:', error);
        } finally {
            document.getElementById('tableLoading').style.display = 'none';
        }
    }

    // Event listeners dengan debounce
    let filterTimeout;
    document.querySelectorAll('.filter-input').forEach(input => {
        input.addEventListener('input', function() {
            clearTimeout(filterTimeout);
            filterTimeout = setTimeout(applyFilters, 500);
        });
    });

    document.querySelectorAll('select.filter-input').forEach(select => {
        select.addEventListener('change', applyFilters);
    });

    // Update URL tanpa reload
    function updateURL(filters) {
        const queryString = new URLSearchParams(filters).toString();
        const newUrl = `${window.location.pathname}?${queryString}`;
        window.history.pushState({ path: newUrl }, '', newUrl);
    }
</script>

<style>
.filter-input {
    border: 1px solid #dee2e6;
    padding: 2px 8px;
    height: 30px;
    font-size: 12px;
}

.filter-input:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

.table th {
    vertical-align: top;
    background-color: #f8f9fa;
}

.badge {
    font-size: 0.75em;
    padding: 0.25em 0.5em;
}
</style>

@endsection

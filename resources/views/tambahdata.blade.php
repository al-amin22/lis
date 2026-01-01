@extends('layouts.app')
<style>
    .dropdown-results {
        position: absolute;
        width: 100%;
        max-height: 200px;
        overflow-y: auto;
        background: white;
        border: 1px solid #dee2e6;
        border-top: none;
        border-radius: 0 0 4px 4px;
        z-index: 1000;
        display: none;
    }

    .dropdown-item {
        padding: 8px 12px;
        cursor: pointer;
        border-bottom: 1px solid #f8f9fa;
    }

    .dropdown-item:hover {
        background-color: #f8f9fa;
    }

    .dropdown-item:last-child {
        border-bottom: none;
    }

    .position-relative {
        position: relative;
    }
</style>
@section('content')
<!-- App hero header starts -->
<div class="app-hero-header d-flex align-items-center">
    <!-- Breadcrumb starts -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <i class="ri-home-8-line lh-1 pe-3 me-3 border-end"></i>
            <a href="{{ url('admin/dashboard') }}">Home</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('pasien.index') }}">Dashboard</a>
        </li>
        <li class="breadcrumb-item text-primary" aria-current="page">
            Tambah Data Pasien
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

<!-- App body starts -->
<div class="row gx-3">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Tambah Data Pasien</h5>
            </div>
            <div class="card-body">
                <!-- Form start -->
                <form action="{{ route('pasien.store') }}" method="POST">
                    @csrf

                    <!-- Row starts -->
                    <div class="row gx-3">

                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label" for="a1">RM Pasien</label>
                                <input type="text" class="form-control" id="a1" name="rm_pasien" placeholder="Masukkan RM Pasien jika ada">
                            </div>
                        </div>

                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label" for="a2">No. Registrasi Lab</label>
                                <input type="text" class="form-control" id="a2" name="no_lab"
                                    value="{{ old('no_lab', $nextLabNumber) }}"
                                    placeholder="Masukkan No. Registrasi Lab Jika Ada">
                            </div>
                        </div>

                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label">Nama Pasien</label>
                                <input type="text" class="form-control" name="nama_pasien"
                                    placeholder="Masukkan Nama Lengkap">
                            </div>
                        </div>

                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label">Tgl Lahir</label>
                                <input type="date" class="form-control" name="tgl_lahir"
                                    max="{{ date('Y-m-d') }}">
                            </div>
                        </div>

                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label">Jenis Kelamin</label>
                                <select class="form-select" name="jenis_kelamin">
                                    <option value="">Select</option>
                                    <option value="PRIA">Laki-Laki</option>
                                    <option value="WANITA">Perempuan</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label">No. Telepon</label>
                                <input type="text" class="form-control" name="no_telepon"
                                    placeholder="Masukkan No. Telepon">
                            </div>
                        </div>

                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label">Alamat</label>
                                <input type="text" class="form-control" name="alamat"
                                    placeholder="Masukkan Alamat Lengkap">
                            </div>
                        </div>
                        <!-- Penjamin -->
                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label">Penjamin</label>
                                <input type="text"
                                    class="form-control dropdown-search"
                                    name="nota"
                                    id="penjamin-input"
                                    placeholder="Ketik untuk mencari penjamin..."
                                    autocomplete="off"
                                    data-type="penjamin">
                                <input type="hidden" name="nota_hidden" id="penjamin-hidden">
                                <div class="dropdown-results" id="penjamin-results"></div>
                            </div>
                        </div>

                        <!-- Kelas -->
                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label">Kelas</label>
                                <input type="text"
                                    class="form-control dropdown-search"
                                    name="kelas_nama"
                                    id="kelas-input"
                                    placeholder="Ketik untuk mencari kelas/Kosongkan Jika TIdak Ada..."
                                    autocomplete="off"
                                    data-type="kelas">
                                <input type="hidden" name="id_kelas" id="kelas-hidden">
                                <div class="dropdown-results" id="kelas-results"></div>
                            </div>
                        </div>

                        <!-- Ruangan -->
                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label">Ruangan</label>
                                <input type="text"
                                    class="form-control dropdown-search"
                                    name="ket_klinik"
                                    id="ruangan-input"
                                    placeholder="Ketik untuk mencari ruangan..."
                                    autocomplete="off"
                                    data-type="ruangan">
                                <div class="dropdown-results" id="ruangan-results"></div>
                            </div>
                        </div>

                        <!-- Validator -->
                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label">Validator</label>
                                <input type="text"
                                    class="form-control dropdown-search"
                                    name="validator_nama"
                                    id="validator-input"
                                    placeholder="Ketik untuk mencari validator..."
                                    autocomplete="off"
                                    data-type="validator">
                                <input type="hidden" name="id_pemeriksa" id="validator-hidden">
                                <div class="dropdown-results" id="validator-results"></div>
                            </div>
                        </div>

                        <!-- Pengirim -->
                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label">Pengirim</label>
                                <input type="text"
                                    class="form-control dropdown-search"
                                    name="pengirim"
                                    id="pengirim-input"
                                    placeholder="Ketik untuk mencari pengirim..."
                                    autocomplete="off"
                                    data-type="pengirim">
                                <div class="dropdown-results" id="pengirim-results"></div>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="d-flex gap-2 justify-content-end">
                                <a href="{{ route('pasien.index') }}" class="btn btn-outline-secondary">
                                    Kembali Ke Dashboard
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    Simpan
                                </button>
                            </div>
                        </div>

                    </div>
                    <!-- Row ends -->
                </form>

                <!-- Form end -->
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Debounce function untuk limit request
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // AJAX search function dengan error handling lengkap
    async function searchDropdown(type, query) {
        const resultsDiv = document.getElementById(`${type}-results`);
        const input = document.getElementById(`${type}-input`);

        if (!resultsDiv || !input) {
            console.error(`[Search Error] Element tidak ditemukan untuk type: ${type}`);
            return;
        }

        // Clear results jika query kosong
        if (query.length < 1) {
            resultsDiv.innerHTML = '';
            resultsDiv.style.display = 'none';
            return;
        }

        // Tampilkan loading state
        resultsDiv.innerHTML = '<div class="dropdown-item text-muted">Mencari...</div>';
        resultsDiv.style.display = 'block';
        input.classList.add('searching');

        try {
            console.log(`[Search Start] Type: ${type}, Query: "${query}"`);

            const response = await fetch(`/admin/search-dropdown?type=${type}&search=${encodeURIComponent(query)}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            console.log(`[Search Response] Status: ${response.status}, OK: ${response.ok}`);

            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }

            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                throw new Error('Response bukan JSON!');
            }

            const data = await response.json();
            console.log(`[Search Data] Type: ${type}, Jumlah hasil: ${data.length || 0}`);

            if (Array.isArray(data)) {
                if (data.length > 0) {
                    let html = '';
                    data.forEach((item, index) => {
                        let displayText = '';
                        let value = '';

                        try {
                            switch(type) {
                                case 'penjamin':
                                    if (!item.nama_penjamin) {
                                        console.warn(`[Search Warning] Item penjamin ke-${index} tidak memiliki nama_penjamin`);
                                        return;
                                    }
                                    displayText = item.nama_penjamin;
                                    value = item.nama_penjamin;
                                    break;
                                case 'kelas':
                                    if (!item.nama_kelas || !item.id_kelas) {
                                        console.warn(`[Search Warning] Item kelas ke-${index} tidak memiliki nama_kelas atau id_kelas`);
                                        return;
                                    }
                                    displayText = item.nama_kelas;
                                    value = item.id_kelas;
                                    break;
                                case 'ruangan':
                                    if (!item.nama_ruangan) {
                                        console.warn(`[Search Warning] Item ruangan ke-${index} tidak memiliki nama_ruangan`);
                                        return;
                                    }
                                    displayText = item.nama_ruangan;
                                    value = item.nama_ruangan;
                                    break;
                                case 'validator':
                                    if (!item.nama_pemeriksa || !item.id_pemeriksa) {
                                        console.warn(`[Search Warning] Item validator ke-${index} tidak memiliki nama_pemeriksa atau id_pemeriksa`);
                                        return;
                                    }
                                    displayText = item.nama_pemeriksa;
                                    value = item.id_pemeriksa;
                                    break;
                                case 'pengirim':
                                    if (!item.nama_dokter) {
                                        console.warn(`[Search Warning] Item pengirim ke-${index} tidak memiliki nama_dokter`);
                                        return;
                                    }
                                    displayText = item.nama_dokter;
                                    value = item.nama_dokter;
                                    break;
                                default:
                                    console.error(`[Search Error] Type tidak dikenali: ${type}`);
                                    return;
                            }

                            // Escape special characters untuk onclick
                            const escapedValue = value.toString().replace(/'/g, "\\'").replace(/"/g, '&quot;');
                            const escapedText = displayText.toString().replace(/'/g, "\\'").replace(/"/g, '&quot;');

                            html += `<div class="dropdown-item"
                                       onclick="selectDropdownItem('${type}', '${escapedValue}', '${escapedText}')"
                                       data-value="${escapedValue}"
                                       data-text="${escapedText}">
                                       ${displayText}
                                     </div>`;
                        } catch (itemError) {
                            console.error(`[Search Error] Gagal memproses item ke-${index}:`, itemError);
                            console.error('Item data:', item);
                        }
                    });

                    if (html === '') {
                        resultsDiv.innerHTML = '<div class="dropdown-item text-muted">Data ditemukan tapi format tidak valid</div>';
                    } else {
                        resultsDiv.innerHTML = html;
                    }
                } else {
                    resultsDiv.innerHTML = '<div class="dropdown-item text-muted">Tidak ditemukan</div>';
                }
            } else {
                console.error('[Search Error] Response bukan array:', data);
                resultsDiv.innerHTML = '<div class="dropdown-item text-danger">Format data tidak valid</div>';
            }

        } catch (error) {
            console.error(`[Search Error] Type: ${type}, Query: "${query}"`, error);

            let errorMessage = 'Gagal mencari data';

            if (error.message.includes('Failed to fetch')) {
                errorMessage = 'Koneksi jaringan bermasalah';
                console.error('[Network Error] Periksa koneksi internet dan URL endpoint');
            } else if (error.message.includes('JSON')) {
                errorMessage = 'Format data tidak valid';
                console.error('[JSON Error] Server tidak mengembalikan JSON yang valid');
            } else if (error.message.includes('HTTP')) {
                errorMessage = `Error server (${error.message})`;
                console.error('[HTTP Error] Periksa endpoint dan server status');
            }

            resultsDiv.innerHTML = `<div class="dropdown-item text-danger">
                <i class="ri-error-warning-line me-1"></i>
                ${errorMessage}
            </div>`;

            // Log detail error untuk debugging
            console.error('Error details:', {
                type: type,
                query: query,
                errorName: error.name,
                errorMessage: error.message,
                errorStack: error.stack,
                timestamp: new Date().toISOString()
            });

        } finally {
            // Hilangkan loading state
            input.classList.remove('searching');
            console.log(`[Search Complete] Type: ${type}, Query: "${query}"`);
        }
    }

    // Select item dari dropdown dengan error handling
    function selectDropdownItem(type, value, text) {
        try {
            console.log(`[Select Item] Type: ${type}, Value: ${value}, Text: ${text}`);

            const input = document.getElementById(`${type}-input`);
            const hiddenInput = document.getElementById(`${type}-hidden`);
            const resultsDiv = document.getElementById(`${type}-results`);

            if (!input) {
                throw new Error(`Input element untuk ${type} tidak ditemukan`);
            }

            // Validasi input
            if (typeof text !== 'string' || text.trim() === '') {
                throw new Error('Text tidak valid');
            }

            input.value = text;

            if (hiddenInput) {
                if (typeof value !== 'string' && typeof value !== 'number') {
                    console.warn(`[Select Warning] Value untuk ${type} bukan string/number:`, value);
                }
                hiddenInput.value = value;
                console.log(`[Hidden Value Set] ${type}: ${value}`);
            }

            if (resultsDiv) {
                resultsDiv.style.display = 'none';
                resultsDiv.innerHTML = '';
            }

            // Trigger event untuk form validation jika ada
            input.dispatchEvent(new Event('change', { bubbles: true }));

            console.log(`[Selection Complete] ${type}: "${text}" dipilih`);

        } catch (error) {
            console.error('[Selection Error] Gagal memilih item:', error);
            alert('Terjadi kesalahan saat memilih data. Silakan coba lagi.');
        }
    }

    // Initialize search functionality dengan error handling
    document.addEventListener('DOMContentLoaded', function() {
        console.log('[Search Init] Memulai inisialisasi search dropdown');

        try {
            // Setup semua input search
            const searchInputs = document.querySelectorAll('.dropdown-search');

            if (searchInputs.length === 0) {
                console.warn('[Search Init] Tidak ditemukan elemen dengan class .dropdown-search');
            } else {
                console.log(`[Search Init] Ditemukan ${searchInputs.length} input search`);
            }

            searchInputs.forEach((input, index) => {
                try {
                    const type = input.dataset.type;

                    if (!type) {
                        console.error(`[Search Init Error] Input #${index} tidak memiliki data-type`);
                        return;
                    }

                    console.log(`[Search Init] Mengatur input #${index} untuk type: ${type}`);

                    // Debounce search untuk limit request
                    const debouncedSearch = debounce((query) => {
                        console.log(`[Input Event] Type: ${type}, Query: "${query}"`);
                        searchDropdown(type, query);
                    }, 300);

                    // Event listener untuk input
                    input.addEventListener('input', function(e) {
                        try {
                            debouncedSearch(e.target.value);
                        } catch (error) {
                            console.error(`[Input Event Error] Type: ${type}:`, error);
                        }
                    });

                    // Event listener untuk keydown (optional)
                    input.addEventListener('keydown', function(e) {
                        if (e.key === 'Escape') {
                            const resultsDiv = document.getElementById(`${type}-results`);
                            if (resultsDiv) {
                                resultsDiv.style.display = 'none';
                            }
                        }
                    });

                    // Tambahkan position relative ke parent
                    const parent = input.parentElement;
                    if (parent) {
                        parent.classList.add('position-relative');
                    } else {
                        console.warn(`[Search Init] Parent element tidak ditemukan untuk ${type}`);
                    }

                    // Show results on focus
                    input.addEventListener('focus', function() {
                        console.log(`[Focus Event] Type: ${type}, Value: "${this.value}"`);
                        if (this.value.length > 0) {
                            searchDropdown(type, this.value);
                        }
                    });

                    // Log success
                    console.log(`[Search Init Success] Input ${type} berhasil diinisialisasi`);

                } catch (inputError) {
                    console.error(`[Search Init Error] Gagal mengatur input #${index}:`, inputError);
                }
            });

            // Setup global click untuk menyembunyikan dropdown
            document.addEventListener('click', function(e) {
                searchInputs.forEach(input => {
                    const type = input.dataset.type;
                    if (!type) return;

                    const resultsDiv = document.getElementById(`${type}-results`);
                    if (!resultsDiv) return;

                    if (!input.contains(e.target) && !resultsDiv.contains(e.target)) {
                        resultsDiv.style.display = 'none';
                    }
                });
            });

            // Fungsi perhitungan umur (existing) dengan error handling
            const dobInput = document.getElementById('a12');
            const ageInput = document.getElementById('a13');

            if (dobInput && ageInput) {
                dobInput.addEventListener('change', function() {
                    try {
                        let tglLahir = new Date(this.value);
                        let today = new Date();

                        // Validasi tanggal
                        if (isNaN(tglLahir.getTime())) {
                            console.error('[Age Calculation] Format tanggal tidak valid');
                            ageInput.value = 'Tanggal tidak valid';
                            return;
                        }

                        // Validasi tanggal masa depan
                        if (tglLahir > today) {
                            console.warn('[Age Calculation] Tanggal lahir di masa depan');
                            ageInput.value = 'Tanggal tidak valid';
                            return;
                        }

                        let tahun = today.getFullYear() - tglLahir.getFullYear();
                        let bulan = today.getMonth() - tglLahir.getMonth();
                        let hari = today.getDate() - tglLahir.getDate();

                        if (hari < 0) {
                            bulan--;
                            let lastMonth = new Date(today.getFullYear(), today.getMonth(), 0);
                            hari += lastMonth.getDate();
                        }

                        if (bulan < 0) {
                            tahun--;
                            bulan += 12;
                        }

                        let umurText = tahun + " Tahun " + bulan + " Bulan " + hari + " Hari";
                        ageInput.value = tahun >= 0 ? umurText : "0 Tahun 0 Bulan 0 Hari";

                        console.log('[Age Calculation] Berhasil:', {
                            tanggal: this.value,
                            umur: ageInput.value
                        });

                    } catch (error) {
                        console.error('[Age Calculation Error]:', error);
                        ageInput.value = 'Error menghitung umur';
                    }
                });
            } else {
                console.warn('[Age Calculation] Input tanggal lahir atau umur tidak ditemukan');
            }

            console.log('[Search Init Complete] Semua fungsi berhasil diinisialisasi');

        } catch (initError) {
            console.error('[Search Init Failed] Inisialisasi gagal:', initError);
            alert('Terjadi kesalahan dalam menginisialisasi fitur pencarian. Silakan refresh halaman.');
        }
    });

    // Global error handler untuk uncaught errors
    window.addEventListener('error', function(event) {
        console.error('[Global Error] Uncaught error:', {
            message: event.message,
            filename: event.filename,
            lineno: event.lineno,
            colno: event.colno,
            error: event.error
        });
    });

    // Log untuk memastikan script loaded
    console.log('[Script Loaded] AJAX Search script berhasil dimuat');
</script>

<!-- Script khusus untuk halaman tambah data pasien -->
<script>
    $(document).ready(function() {
        // Inisialisasi tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // Contoh fungsi untuk halaman tambah data
        console.log('Halaman tambah data pasien loaded');

        // Validasi form sederhana
        $('#patientForm').on('submit', function(e) {
            e.preventDefault();
            // Logika validasi dan submit form
            console.log('Form submitted');
        });
    });
</script>

<script>
    document.getElementById('a12').addEventListener('change', function() {
        let tglLahir = new Date(this.value);
        let today = new Date();

        let tahun = today.getFullYear() - tglLahir.getFullYear();
        let bulan = today.getMonth() - tglLahir.getMonth();
        let hari = today.getDate() - tglLahir.getDate();

        // Koreksi selisih bulan & hari
        if (hari < 0) {
            bulan--;
            let lastMonth = new Date(today.getFullYear(), today.getMonth(), 0);
            hari += lastMonth.getDate();
        }

        if (bulan < 0) {
            tahun--;
            bulan += 12;
        }

        // Format hasil
        let umurText = tahun + " Tahun " + bulan + " Bulan " + hari + " Hari";
        document.getElementById('a13').value = tahun >= 0 ? umurText : "0 Tahun 0 Bulan 0 Hari";
    });
</script>
@endsection


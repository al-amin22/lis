// File: public/js/hasil-lain-realtime.js
class HasilLainRealtimeSystem {
    constructor() {
        this.csrfToken = $('meta[name="csrf-token"]').attr('content') || $('#csrf_token').val();
        this.init();
    }

    init() {
        console.log('🔧 HASIL LAIN - REALTIME SYSTEM LOADED');
        this.bindEvents();
        this.initializeAllRows();
    }

    bindEvents() {
        // Event untuk input hasil pengujian
        $(document).on('input', '.hasil-input-lain', this.handleHasilInput.bind(this));

        // Event untuk tombol enter
        $(document).on('keydown', '.hasil-input-lain', this.handleKeydown.bind(this));

        // Event untuk blur
        $(document).on('blur', '.hasil-input-lain', this.handleBlur.bind(this));

        // Event untuk search input
        $(document).on('input', '.kode-search-input-lain', this.handleSearchInput.bind(this));

        // Event untuk click search result
        $(document).on('click', '.search-result-item', this.handleSearchResultClick.bind(this));
    }

    handleHasilInput(e) {
        const $input = $(e.target);
        const value = $input.val().trim();

        // Update tampilan keterangan secara realtime
        this.updateKeteranganRealtime($input, value);

        // Clear timer sebelumnya
        clearTimeout($input.data('save-timer'));

        // Set timer untuk debounce save (1.5 detik setelah berhenti mengetik)
        $input.data('save-timer', setTimeout(() => {
            this.saveHasil($input, value);
        }, 1500));
    }

    handleKeydown(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const $input = $(e.target);
            const value = $input.val().trim();

            // Clear timer
            clearTimeout($input.data('save-timer'));

            // Save immediately
            this.saveHasil($input, value);

            // Pindah ke input berikutnya atau tetap fokus
            $input.blur();
        }
    }

    handleBlur(e) {
        const $input = $(e.target);
        const value = $input.val().trim();

        // Clear timer
        clearTimeout($input.data('save-timer'));

        // Save jika ada perubahan
        if (value !== $input.data('last-saved-value')) {
            this.saveHasil($input, value);
        }
    }

    async saveHasil($input, value) {
        // Cek apakah value sama dengan yang terakhir disimpan
        if (value === $input.data('last-saved-value')) {
            return;
        }

        const $row = $input.closest('tr');
        const id = this.getRowId($row);
        const isNew = this.isNewRow($row);

        // Validasi data yang diperlukan
        if (!this.validateRowData($row, isNew)) {
            return;
        }

        // Prepare data
        const data = this.prepareSaveData($row, value, isNew);

        // Add visual feedback
        this.showSavingFeedback($input);

        try {
            let response;

            if (isNew) {
                // CREATE new record
                response = await this.saveCreate(data);

                if (response.success) {
                    // Update row dengan ID baru
                    this.updateRowWithNewId($row, response.data.id_hasil_lain);
                    $input.data('last-saved-value', value);
                    this.showSuccessFeedback($input, 'Data berhasil disimpan');
                }
            } else {
                // UPDATE existing record
                response = await this.saveUpdate(id, data);

                if (response.success) {
                    $input.data('last-saved-value', value);
                    this.showSuccessFeedback($input, 'Data berhasil diperbarui');
                }
            }

            // Update keterangan dari response
            if (response.success && response.data.keterangan) {
                this.updateKeteranganDisplay($row, response.data.keterangan);
            }

        } catch (error) {
            console.error('Save error:', error);
            this.showErrorFeedback($input, 'Gagal menyimpan: ' + error.message);
        }
    }

    async saveCreate(data) {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: '/hasil-lain/store-realtime',
                method: 'POST',
                data: {
                    _token: this.csrfToken,
                    ...data
                },
                success: function(response) {
                    resolve(response);
                },
                error: function(xhr) {
                    reject(new Error(xhr.responseJSON?.message || 'Server error'));
                }
            });
        });
    }

    async saveUpdate(id, data) {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: '/hasil-lain/update-realtime',
                method: 'POST',
                data: {
                    _token: this.csrfToken,
                    id: id,
                    hasil_pengujian: data.hasil_pengujian,
                    keterangan: data.keterangan
                },
                success: function(response) {
                    resolve(response);
                },
                error: function(xhr) {
                    reject(new Error(xhr.responseJSON?.message || 'Server error'));
                }
            });
        });
    }

    getRowId($row) {
        return $row.attr('data-id') ||
               $row.data('id') ||
               $row.find('input[name*="[id]"]').val();
    }

    isNewRow($row) {
        const id = this.getRowId($row);
        return !id || id.toString().includes('temp_');
    }

    validateRowData($row, isNew) {
        const idDataPemeriksaan = $row.find('.kode-pemeriksaan-input').val();

        if (!idDataPemeriksaan) {
            this.showToast('warning', 'Pilih pemeriksaan terlebih dahulu');
            $row.find('.kode-search-input-lain').focus();
            return false;
        }

        if (isNew) {
            const noLab = $row.closest('form').find('input[name="no_lab"]').val();
            if (!noLab) {
                this.showToast('error', 'No LAB tidak ditemukan');
                return false;
            }
        }

        return true;
    }

    prepareSaveData($row, value, isNew) {
        const data = {
            hasil_pengujian: value,
            keterangan: $row.find('input[name*="[keterangan]"]').val() || '-'
        };

        if (isNew) {
            data.no_lab = $row.closest('form').find('input[name="no_lab"]').val();
            data.id_data_pemeriksaan = $row.find('.kode-pemeriksaan-input').val();
            data.jenis_pengujian = $row.find('input[name*="[jenis_pengujian]"]').val();
            data.satuan = $row.find('.satuan-display').text().trim();
            data.rujukan = $row.find('.rujukan-display').text().trim();
            data.ch = $row.find('.ch-display').text().trim();
            data.cl = $row.find('.cl-display').text().trim();
        }

        return data;
    }

    updateRowWithNewId($row, newId) {
        // Update semua atribut ID
        $row.attr('data-id', newId);
        $row.data('id', newId);

        // Update hidden input
        $row.find('input[name*="[id]"]').val(newId);

        // Update data-id di input hasil
        $row.find('.hasil-input-lain')
            .attr('data-id', newId)
            .data('id', newId)
            .removeAttr('data-temp-id')
            .removeData('temp-id');

        // Update search input
        $row.find('.kode-search-input-lain')
            .attr('data-row-id', newId)
            .removeAttr('data-temp-id');

        // Hapus temp attributes
        $row.removeAttr('data-temp-id');
        $row.removeData('temp-id');
    }

    updateKeteranganRealtime($input, value) {
        const $row = $input.closest('tr');
        const rujukan = $input.data('rujukan') || $row.find('.rujukan-display').text().trim();
        const ch = $input.data('ch') || $row.find('.ch-display').text().trim();
        const cl = $input.data('cl') || $row.find('.cl-display').text().trim();

        // Hitung keterangan menggunakan fungsi global jika ada
        let keterangan = '-';
        if (typeof window.calculateKeterangan === 'function') {
            $input.data('rujukan', rujukan)
                  .data('ch', ch)
                  .data('cl', cl);

            keterangan = window.calculateKeterangan(value, $input);
        }

        // Update display
        this.updateKeteranganDisplay($row, keterangan);

        // Update hidden input
        $row.find('input[name*="[keterangan]"]').val(keterangan);

        return keterangan;
    }

    updateKeteranganDisplay($row, keterangan) {
        const $display = $row.find('.keterangan-display');
        const colors = {
            'H': { bg: 'bg-danger bg-opacity-10', text: 'text-danger', textDisplay: 'H' },
            'L': { bg: 'bg-primary bg-opacity-10', text: 'text-primary', textDisplay: 'L' },
            'CH': { bg: 'bg-danger bg-opacity-10', text: 'text-danger', textDisplay: 'CH' },
            'CL': { bg: 'bg-primary bg-opacity-10', text: 'text-primary', textDisplay: 'CL' },
            '-': { bg: 'bg-success bg-opacity-10', text: 'text-success', textDisplay: '-' }
        };

        const style = colors[keterangan] || colors['-'];

        $display
            .removeClass('bg-danger bg-opacity-10 bg-primary bg-opacity-10 bg-success bg-opacity-10')
            .removeClass('text-danger text-primary text-success')
            .addClass(style.bg + ' ' + style.text)
            .data('keterangan', keterangan)
            .html(`<strong>${style.textDisplay}</strong>`);
    }

    showSavingFeedback($input) {
        $input.addClass('is-saving');
        $input.closest('td').addClass('table-warning');
    }

    showSuccessFeedback($input, message) {
        $input.removeClass('is-saving').addClass('is-saved');
        $input.closest('td')
            .removeClass('table-warning')
            .addClass('table-success');

        this.showToast('success', message);

        setTimeout(() => {
            $input.closest('td').removeClass('table-success');
            $input.removeClass('is-saved');
        }, 2000);
    }

    showErrorFeedback($input, message) {
        $input.removeClass('is-saving').addClass('has-error');
        $input.closest('td')
            .removeClass('table-warning')
            .addClass('table-danger');

        this.showToast('error', message);

        setTimeout(() => {
            $input.closest('td').removeClass('table-danger');
        }, 3000);
    }

    showToast(type, message) {
        const toastHtml = `
            <div class="toast align-items-center text-white bg-${type} border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="ri-${type === 'success' ? 'check' : 'error'}-circle-fill me-2"></i>
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        `;

        const $container = $('#toastContainer');
        if ($container.length === 0) {
            $('body').append('<div id="toastContainer" class="toast-container position-fixed top-0 end-0 p-3"></div>');
        }

        const $toast = $(toastHtml);
        $('#toastContainer').append($toast);

        const toast = new bootstrap.Toast($toast[0], { delay: 3000 });
        toast.show();

        $toast.on('hidden.bs.toast', function() {
            $(this).remove();
        });
    }

    initializeAllRows() {
        $('.hasil-input-lain').each((index, input) => {
            const $input = $(input);
            const $row = $input.closest('tr');

            // Set last saved value
            $input.data('last-saved-value', $input.val());

            // Initialize data attributes
            this.initializeRowData($row);
        });
    }

    initializeRowData($row) {
        const $input = $row.find('.hasil-input-lain');
        const idDataPemeriksaan = $row.find('.kode-pemeriksaan-input').val();

        if (idDataPemeriksaan) {
            $input.data({
                'id_data_pemeriksaan': idDataPemeriksaan,
                'rujukan': $row.find('.rujukan-display').text().trim(),
                'ch': $row.find('.ch-display').text().trim(),
                'cl': $row.find('.cl-display').text().trim(),
                'jenis': $row.find('input[name*="[jenis_pengujian]"]').val()
            });
        }
    }

    // Search functionality
    handleSearchInput(e) {
        const $input = $(e.target);
        const searchTerm = $input.val().trim();
        const $results = $input.siblings('.kode-search-results');

        if (searchTerm.length < 2) {
            $results.hide().empty();
            return;
        }

        // Debounce search
        clearTimeout($input.data('search-timer'));
        $input.data('search-timer', setTimeout(() => {
            this.performSearch($input, searchTerm, $results);
        }, 300));
    }

    performSearch($input, searchTerm, $results) {
        const jenisPemeriksaan = $input.data('jenis-pemeriksaan');
        const currentId = $input.siblings('.kode-pemeriksaan-input').val();

        $results.html('<div class="dropdown-item text-muted small">Mencari...</div>').show();

        $.ajax({
            url: '/hasil-lain/search-kode-pemeriksaan',
            method: 'POST',
            data: {
                _token: this.csrfToken,
                search: searchTerm,
                jenis_pemeriksaan: jenisPemeriksaan,
                exclude_current: currentId || ''
            },
            success: (response) => {
                if (response.success && response.data.length > 0) {
                    this.displaySearchResults(response.data, $input, $results);
                } else {
                    $results.html('<div class="dropdown-item text-muted small">Tidak ditemukan</div>').show();
                }
            },
            error: () => {
                $results.html('<div class="dropdown-item text-danger small">Error saat mencari</div>').show();
            }
        });
    }

    displaySearchResults(data, $input, $results) {
        $results.empty();

        data.forEach((item) => {
            const $item = $(`
                <a href="#" class="dropdown-item small search-result-item"
                   data-id="${item.id_data_pemeriksaan}"
                   data-nama="${item.data_pemeriksaan}"
                   data-satuan="${item.satuan || ''}"
                   data-rujukan="${item.rujukan || ''}"
                   data-ch="${item.ch || ''}"
                   data-cl="${item.cl || ''}">
                    <div class="d-flex justify-content-between">
                        <span>${item.data_pemeriksaan}</span>
                        <small class="text-muted">${item.id_data_pemeriksaan}</small>
                    </div>
                    <small class="text-muted d-block">${item.satuan || ''}</small>
                </a>
            `);

            $results.append($item);
        });

        $results.show();
    }

    handleSearchResultClick(e) {
        e.preventDefault();
        const $item = $(e.target).closest('.search-result-item');
        const data = $item.data();
        const $input = $item.closest('.position-relative').find('.kode-search-input-lain');
        const $row = $input.closest('tr');

        // Update row dengan data baru
        this.updateRowFromSearch($row, data);

        // Hide results
        $input.siblings('.kode-search-results').hide();
    }

    updateRowFromSearch($row, data) {
        // Update input values
        $row.find('.kode-search-input-lain').val(data.nama);
        $row.find('.kode-pemeriksaan-input').val(data.id);
        $row.find('.satuan-display').text(data.satuan || '-');
        $row.find('.rujukan-display').text(data.rujukan || '-');
        $row.find('.ch-display').text(data.ch || '-');
        $row.find('.cl-display').text(data.cl || '-');

        // Update hidden inputs
        $row.find('input[name*="[ch]"]').val(data.ch || '');
        $row.find('input[name*="[cl]"]').val(data.cl || '');
        $row.find('input[name*="[jenis_pengujian]"]').val(data.nama);

        // Update data attributes
        const $hasilInput = $row.find('.hasil-input-lain');
        $hasilInput.data({
            'rujukan': data.rujukan || '',
            'ch': data.ch || '',
            'cl': data.cl || '',
            'id_data_pemeriksaan': data.id,
            'jenis': data.nama
        });

        // Focus ke input hasil
        setTimeout(() => {
            $hasilInput.focus();
        }, 100);
    }
}

// Inisialisasi sistem saat dokumen ready
$(document).ready(function() {
    window.hasilLainSystem = new HasilLainRealtimeSystem();
});

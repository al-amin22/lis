// Counter untuk row index baru
let pemeriksaanLainCounter = {{ $pemeriksaanLain ? $pemeriksaanLain->count() : 0 }};

console.log('pemeriksaanLainCounter initialized:', pemeriksaanLainCounter);
console.log('Button exists:', $('#addPemeriksaanLainBtn').length);

// Function untuk menambahkan baris baru
function addPemeriksaanLainRow(data = {}) {
    console.log('addPemeriksaanLainRow called with data:', data);
    console.log('Current counter:', pemeriksaanLainCounter);

    const index = pemeriksaanLainCounter++;
    const rowId = 'new_' + Date.now() + '_' + index;

    console.log('Creating row with index:', index, 'rowId:', rowId);

    // Buat options untuk select
    let optionsHtml = '<option value="">-- Pilih --</option>';
    @if(isset($jenisPemeriksaanList) && $jenisPemeriksaanList)
        @foreach($jenisPemeriksaanList as $jenis)
            optionsHtml += '<option value="{{ $jenis->id_jenis_pemeriksaan }}">';
            optionsHtml += '{{ $jenis->nama_jenis_pemeriksaan }}';
            optionsHtml += '</option>';
        @endforeach
    @endif

    const rowHtml = `
        <tr id="${rowId}" data-id="${data.id || ''}">
            <td>
                <div class="d-flex gap-2">
                    <input type="text"
                        name="pemeriksaan_lain[${index}][jenis_pengujian]"
                        class="form-control form-control-sm excel-input pemeriksaan-lain-input"
                        value="${data.jenis_pengujian || ''}"
                        placeholder="Jenis pengujian"
                        data-field="jenis_pengujian"
                        data-id="${data.id || ''}"
                        data-original="${data.jenis_pengujian || ''}"
                        autocomplete="off">
                    <select name="pemeriksaan_lain[${index}][id_jenis_pemeriksaan]"
                        class="form-control form-control-sm"
                        style="min-width: 100px;">
                        ${optionsHtml}
                    </select>
                </div>
                <input type="hidden"
                    name="pemeriksaan_lain[${index}][id]"
                    value="${data.id || ''}">
            </td>
            <td>
                <input type="text"
                    name="pemeriksaan_lain[${index}][hasil_pengujian]"
                    class="form-control form-control-sm excel-input hasil-pemeriksaan-lain-input"
                    value="${data.hasil_pengujian || ''}"
                    placeholder="Hasil"
                    data-field="hasil_pengujian"
                    data-id="${data.id || ''}"
                    data-original="${data.hasil_pengujian || ''}"
                    data-rujukan="${data.rujukan || ''}"
                    autocomplete="off">
            </td>
            <td>
                <input type="text"
                    name="pemeriksaan_lain[${index}][satuan_hasil_pengujian]"
                    class="form-control form-control-sm excel-input satuan-input"
                    value="${data.satuan_hasil_pengujian || ''}"
                    placeholder="Satuan"
                    data-field="satuan_hasil_pengujian"
                    data-id="${data.id || ''}"
                    data-original="${data.satuan_hasil_pengujian || ''}"
                    autocomplete="off">
            </td>
            <td>
                <input type="text"
                    name="pemeriksaan_lain[${index}][rujukan]"
                    class="form-control form-control-sm excel-input rujukan-input"
                    value="${data.rujukan || ''}"
                    placeholder="Rujukan"
                    data-field="rujukan"
                    data-id="${data.id || ''}"
                    data-original="${data.rujukan || ''}"
                    autocomplete="off">
            </td>
            <td class="keterangan-cell">
                <div class="keterangan-display bg-success bg-opacity-10 text-success rounded py-1 px-2 text-center"
                    data-keterangan="-">
                    <strong></strong>
                </div>
                <input type="hidden"
                    name="pemeriksaan_lain[${index}][keterangan]"
                    value="-">
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-danger remove-pemeriksaan-btn">
                    <i class="ri-delete-bin-line"></i>
                </button>
            </td>
        </tr>
    `;

    console.log('Appending row to #pemeriksaanLainBody');
    console.log('#pemeriksaanLainBody exists:', $('#pemeriksaanLainBody').length);

    $('#pemeriksaanLainBody').append(rowHtml);

    console.log('Row appended, now focusing on input');

    // Focus ke input pertama di baris baru
    setTimeout(() => {
        const $input = $(`#${rowId} .pemeriksaan-lain-input`);
        if ($input.length) {
            $input.focus().select();
            console.log('Input focused');
        } else {
            console.log('Input not found!');
        }
    }, 100);

    return rowId;
}

// Event handler untuk tambah pemeriksaan
$('#addPemeriksaanLainBtn').on('click', function(e) {
    console.log('Add button clicked!', e);
    e.preventDefault();
    e.stopPropagation();

    try {
        addPemeriksaanLainRow();
        console.log('Row added successfully');
    } catch (error) {
        console.error('Error adding row:', error);
        showToast('danger', 'Gagal menambahkan baris: ' + error.message);
    }
});

// Debug: Coba panggil langsung untuk testing
console.log('Testing add row function...');
try {
    // Test: panggil function sekali saat load
    // addPemeriksaanLainRow(); // Uncomment untuk testing
} catch (error) {
    console.error('Test failed:', error);
}

// Event delegation untuk input pemeriksaan lain
$(document).on('input', '.hasil-pemeriksaan-lain-input', function() {
    console.log('hasil-pemeriksaan-lain-input changed:', $(this).val());

    const $input = $(this);
    const id = $input.data('id');
    const value = $input.val();
    const rujukan = $input.data('rujukan');

    console.log('Input data:', { id, value, rujukan });

    // Update client-side preview
    updatePemeriksaanLainKeterangan($input);

    // Debounce untuk AJAX call
    clearTimeout($input.data('timer'));
    $input.data('timer', setTimeout(() => {
        console.log('Adding to queue:', { type: 'pemeriksaan_lain', id, field: 'hasil_pengujian', value });
        addToQueue('pemeriksaan_lain', id, 'hasil_pengujian', value, $input);
    }, 800));
});

$(document).on('input', '.rujukan-input', function() {
    console.log('rujukan-input changed:', $(this).val());

    const $input = $(this);
    const id = $input.data('id');
    const value = $input.val();

    // Update data-rujukan pada input hasil
    const $row = $input.closest('tr');
    $row.find('.hasil-pemeriksaan-lain-input').data('rujukan', value);
    console.log('Updated rujukan data attribute');

    clearTimeout($input.data('timer'));
    $input.data('timer', setTimeout(() => {
        console.log('Adding rujukan to queue');
        addToQueue('pemeriksaan_lain', id, 'rujukan', value, $input);
    }, 800));
});

$(document).on('input', '.pemeriksaan-lain-input, .satuan-input', function() {
    console.log('Other input changed:', $(this).val());

    const $input = $(this);
    const id = $input.data('id');
    const value = $input.val();
    const field = $input.data('field');

    clearTimeout($input.data('timer'));
    $input.data('timer', setTimeout(() => {
        console.log('Adding to queue:', { type: 'pemeriksaan_lain', id, field, value });
        addToQueue('pemeriksaan_lain', id, field, value, $input);
    }, 800));
});

// Function untuk update keterangan pemeriksaan lain
function updatePemeriksaanLainKeterangan($input) {
    console.log('updatePemeriksaanLainKeterangan called');

    const hasil = $input.val();
    const rujukan = $input.data('rujukan');
    const $row = $input.closest('tr');
    const $keteranganDisplay = $row.find('.keterangan-display');

    console.log('Data:', { hasil, rujukan });

    if (!hasil || hasil.trim() === '') {
        console.log('Hasil kosong, clearing keterangan');
        updateKeteranganDisplay($keteranganDisplay, '');
        return;
    }

    if (!rujukan || rujukan.trim() === '') {
        console.log('Rujukan kosong, setting to -');
        updateKeteranganDisplay($keteranganDisplay, '-');
        return;
    }

    try {
        const rujukanStr = rujukan.toString().trim();
        const hasilStr = hasil.toString().trim();
        const hasilNum = parseFloat(hasilStr.replace(',', '.'));

        console.log('Parsed:', { hasilNum, hasilStr, rujukanStr });

        if (isNaN(hasilNum)) {
            console.log('Hasil is not a number, checking qualitative');
            const hasilLower = hasilStr.toLowerCase();
            const rujukanLower = rujukanStr.toLowerCase();

            if (rujukanLower.includes('negative') || rujukanLower.includes('negatif')) {
                if (hasilLower.includes('negative') || hasilLower.includes('negatif') ||
                    hasilLower.includes('reactive') || hasilLower.includes('reaktif')) {
                    console.log('Result matches negative reference');
                    updateKeteranganDisplay($keteranganDisplay, '-');
                } else {
                    console.log('Result does not match negative reference');
                    updateKeteranganDisplay($keteranganDisplay, 'H');
                }
            } else if (rujukanLower.includes('positive') || rujukanLower.includes('positif')) {
                if (hasilLower.includes('positive') || hasilLower.includes('positif') ||
                    hasilLower.includes('reactive') || hasilLower.includes('reaktif')) {
                    console.log('Result matches positive reference');
                    updateKeteranganDisplay($keteranganDisplay, '-');
                } else {
                    console.log('Result does not match positive reference');
                    updateKeteranganDisplay($keteranganDisplay, 'L');
                }
            } else {
                console.log('No matching reference pattern');
                updateKeteranganDisplay($keteranganDisplay, '-');
            }
            return;
        }

        if (rujukanStr.includes('-')) {
            console.log('Checking range pattern');
            const parts = rujukanStr.split('-');
            if (parts.length === 2) {
                const min = parseFloat(parts[0].trim());
                const max = parseFloat(parts[1].trim());

                console.log('Range:', { min, max });

                if (!isNaN(min) && !isNaN(max)) {
                    if (hasilNum < min) {
                        console.log('Below minimum');
                        updateKeteranganDisplay($keteranganDisplay, 'L');
                    } else if (hasilNum > max) {
                        console.log('Above maximum');
                        updateKeteranganDisplay($keteranganDisplay, 'H');
                    } else {
                        console.log('Within range');
                        updateKeteranganDisplay($keteranganDisplay, '-');
                    }
                    return;
                }
            }
        }

        if (rujukanStr.includes('<=')) {
            console.log('Checking <= pattern');
            const max = parseFloat(rujukanStr.replace('<=', '').trim());
            if (!isNaN(max)) {
                console.log('Max value:', max);
                updateKeteranganDisplay($keteranganDisplay, hasilNum > max ? 'H' : '-');
                return;
            }
        }

        if (rujukanStr.includes('>=')) {
            console.log('Checking >= pattern');
            const min = parseFloat(rujukanStr.replace('>=', '').trim());
            if (!isNaN(min)) {
                console.log('Min value:', min);
                updateKeteranganDisplay($keteranganDisplay, hasilNum < min ? 'L' : '-');
                return;
            }
        }

        console.log('No pattern matched, default to -');
        updateKeteranganDisplay($keteranganDisplay, '-');

    } catch (e) {
        console.error('Error updating keterangan:', e);
        updateKeteranganDisplay($keteranganDisplay, '-');
    }
}

// Event delegation untuk tombol hapus
$(document).on('click', '.remove-pemeriksaan-btn', function(e) {
    console.log('Remove button clicked');
    e.stopPropagation();

    const $row = $(this).closest('tr');
    const id = $row.data('id');
    const jenisPengujian = $row.find('.pemeriksaan-lain-input').val();

    console.log('Row data:', { id, jenisPengujian });

    if (id && id !== '' && !confirm(`Hapus pemeriksaan "${jenisPengujian}"?`)) {
        console.log('Deletion cancelled');
        return;
    }

    if (id && id !== '') {
        console.log('Sending delete request for id:', id);
        // Kirim request hapus ke server
        $.ajax({
            url: '{{ route("hasil-lab.delete-pemeriksaan-lain", $pasien->no_lab) }}',
            method: 'POST',
            data: {
                _token: csrfToken,
                id: id
            },
            success: function(response) {
                console.log('Delete response:', response);
                if (response.success) {
                    $row.remove();
                    showToast('success', 'Pemeriksaan berhasil dihapus');
                }
            },
            error: function(xhr) {
                console.error('Delete error:', xhr);
                showToast('danger', 'Gagal menghapus pemeriksaan');
            }
        });
    } else {
        console.log('Removing unsaved row from DOM');
        // Hapus dari DOM saja (untuk yang belum disimpan)
        $row.remove();
    }
});

// Update queue processing untuk handle pemeriksaan_lain
function addToQueue(type, id, field, value, $element) {
    console.log('addToQueue called:', { type, id, field, value });

    // Untuk pemeriksaan_lain dengan id kosong, simpan di session
    if (type === 'pemeriksaan_lain' && !id) {
        const tempId = 'temp_' + Date.now();
        $element.data('id', tempId);
        console.log('Assigned temp id:', tempId);
    }

    ajaxQueue.push({
        type: type,
        id: id,
        field: field,
        value: value,
        $element: $element,
        timestamp: Date.now()
    });

    console.log('Queue length:', ajaxQueue.length);

    if (!isProcessingQueue) {
        console.log('Starting queue processing');
        processQueue();
    }
}

// Initialize Excel navigation untuk pemeriksaan lain
function initPemeriksaanLainNavigation() {
    console.log('Initializing pemeriksaan lain navigation');

    $(document).on('focus', '.pemeriksaan-lain-input, .hasil-pemeriksaan-lain-input, .satuan-input, .rujukan-input', function() {
        console.log('Input focused');
        $(this).select();
        $(this).closest('td').addClass('table-warning');
    });

    $(document).on('blur', '.pemeriksaan-lain-input, .hasil-pemeriksaan-lain-input, .satuan-input, .rujukan-input', function() {
        console.log('Input blurred');
        $(this).closest('td').removeClass('table-warning');
    });

    console.log('Pemeriksaan lain navigation initialized');
}

// Initialize setelah document ready
console.log('Document ready, initializing...');

// Panggil function initialization
initPemeriksaanLainNavigation();

// Debug: Check if body exists
console.log('#pemeriksaanLainBody exists:', $('#pemeriksaanLainBody').length);
console.log('#pemeriksaanLainTable exists:', $('#pemeriksaanLainTable').length);

// Debug: Test event binding
$('#addPemeriksaanLainBtn').on('click.test', function() {
    console.log('Test event fired!');
});

// Debug: Simulate click untuk testing
// setTimeout(() => {
//     console.log('Simulating click...');
//     $('#addPemeriksaanLainBtn').trigger('click');
// }, 2000);

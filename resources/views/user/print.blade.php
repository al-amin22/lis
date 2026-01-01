@extends('layouts_user.app')

@section('content')

<div id="laboratorium-preview-wrapper">
    <!-- Konten akan diisi oleh JavaScript -->
</div>

<!-- Styles -->
<style>
    #laboratorium-preview-wrapper {
        all: initial !important;
    }

    #laboratorium-preview-wrapper * {
        all: revert !important;
        box-sizing: border-box !important;
    }

    #laboratorium-preview-wrapper {
        margin: 0 !important;
        padding: 0 !important;
        font-family: Arial, sans-serif !important;
        padding: 20px !important;
        background-color: #f5f5f5 !important;
    }

    #laboratorium-preview-wrapper .container {
        max-width: 1200px !important;
        margin: 0 auto !important;
        background-color: white !important;
        border-radius: 8px !important;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1) !important;
        overflow: hidden !important;
    }

    #laboratorium-preview-wrapper .header {
        background-color: #2c3e50 !important;
        color: white !important;
        padding: 20px !important;
        text-align: center !important;
    }

    #laboratorium-preview-wrapper .header h1 {
        font-size: 24px !important;
        margin-bottom: 10px !important;
    }

    #laboratorium-preview-wrapper .controls {
        background-color: #34495e !important;
        padding: 15px 20px !important;
        display: flex !important;
        gap: 10px !important;
        flex-wrap: wrap !important;
        align-items: center !important;
    }

    #laboratorium-preview-wrapper .btn {
        padding: 10px 20px !important;
        border: none !important;
        border-radius: 4px !important;
        cursor: pointer !important;
        font-weight: bold !important;
        transition: all 0.3s ease !important;
        display: flex !important;
        align-items: center !important;
        gap: 8px !important;
    }

    #laboratorium-preview-wrapper .btn-success {
        background-color: #27ae60 !important;
        color: white !important;
    }

    #laboratorium-preview-wrapper .btn-success:hover {
        background-color: #219653 !important;
    }

    #laboratorium-preview-wrapper .btn-warning {
        background-color: #f39c12 !important;
        color: white !important;
    }

    #laboratorium-preview-wrapper .btn-warning:hover {
        background-color: #d68910 !important;
    }

    #laboratorium-preview-wrapper .preview-container {
        padding: 20px !important;
        background-color: #ecf0f1 !important;
        min-height: 500px !important;
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
    }

    /* UKURAN A4 */
    #laboratorium-preview-wrapper .iframe-wrapper {
        width: 210mm !important;
        height: 297mm !important;
        border: 1px solid #ddd !important;
        background-color: white !important;
        overflow: hidden !important;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2) !important;
        margin: 0 auto !important;
    }

    #laboratorium-preview-wrapper #resultPreview {
        width: 100% !important;
        height: 100% !important;
        border: none !important;
        transform: scale(1) !important;
    }

    #laboratorium-preview-wrapper .loading {
        display: none !important;
        text-align: center !important;
        padding: 20px !important;
        color: #7f8c8d !important;
    }

    #laboratorium-preview-wrapper .status-bar {
        padding: 10px 20px !important;
        background-color: #ecf0f1 !important;
        border-top: 1px solid #ddd !important;
        display: flex !important;
        justify-content: space-between !important;
        color: #666 !important;
        font-size: 14px !important;
    }

    /* IMPORTANT: Print media query */
    @media print {
        #laboratorium-preview-wrapper .header,
        #laboratorium-preview-wrapper .controls,
        #laboratorium-preview-wrapper .status-bar,
        #laboratorium-preview-wrapper .loading {
            display: none !important;
        }

        #laboratorium-preview-wrapper {
            padding: 0 !important;
            margin: 0 !important;
            background: white !important;
        }

        #laboratorium-preview-wrapper .container {
            max-width: 100% !important;
            box-shadow: none !important;
            border-radius: 0 !important;
        }

        #laboratorium-preview-wrapper .preview-container {
            padding: 0 !important;
            background-color: white !important;
            min-height: auto !important;
            display: block !important;
        }

        #laboratorium-preview-wrapper .iframe-wrapper {
            border: none !important;
            box-shadow: none !important;
            width: 100% !important;
            height: auto !important;
            margin: 0 !important;
            transform: none !important;
        }

        #laboratorium-preview-wrapper #resultPreview {
            height: auto !important;
            min-height: 297mm !important;
        }
    }

    @media (max-width: 1200px) {
        #laboratorium-preview-wrapper .iframe-wrapper {
            transform: scale(0.85) !important;
            transform-origin: center !important;
        }
    }

    @media (max-width: 992px) {
        #laboratorium-preview-wrapper .iframe-wrapper {
            transform: scale(0.75) !important;
        }
    }

    @media (max-width: 768px) {
        #laboratorium-preview-wrapper .controls {
            flex-direction: column !important;
            align-items: stretch !important;
        }

        #laboratorium-preview-wrapper .btn {
            width: 100% !important;
            justify-content: center !important;
        }

        #laboratorium-preview-wrapper .iframe-wrapper {
            transform: scale(0.6) !important;
        }
    }

    @media (max-width: 576px) {
        #laboratorium-preview-wrapper .iframe-wrapper {
            transform: scale(0.5) !important;
        }
    }

    #laboratorium-preview-wrapper .info-text {
        color: white !important;
        font-size: 14px !important;
        margin-left: auto !important;
    }
</style>

<!-- Template HTML untuk wrapper -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const wrapper = document.getElementById('laboratorium-preview-wrapper');

        // Masukkan konten ke dalam wrapper
        wrapper.innerHTML = `
        <div class="container">
            <div class="header">
                <h1><i class="fas fa-file-medical-alt"></i> PREVIEW HASIL LABORATORIUM</h1>
                <p>Hasil Pemeriksaan Laboratorium - {{ $nama }}</p>
            </div>

            <div class="controls">
                <button class="btn btn-success" onclick="downloadPDF()">
                    <i class="fas fa-print"></i> Print Hasil
                </button>

                <button class="btn btn-warning" onclick="refreshPreview()">
                    <i class="fas fa-sync-alt"></i> Refresh Preview
                </button>

                <div class="info-text">
                    No. Lab: <strong>{{ $no_lab }}</strong> |
                    Ukuran: <strong>A4 (210mm × 297mm)</strong>
                </div>
            </div>

            <div class="preview-container">
                <div class="loading" id="loading">
                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                    <p>Memuat hasil laboratorium...</p>
                </div>

                <div class="iframe-wrapper">
                    <iframe id="resultPreview" src="about:blank"></iframe>
                </div>
            </div>

            <div class="status-bar">
                <div>
                    <i class="fas fa-info-circle"></i>
                    Dokter Penanggung Jawab: <strong>{{ $dokter_penanggung_jawab }}</strong>
                </div>
                <div>
                    Status: <span id="statusText">Loading...</span> |
                    Preview Scale: <span id="scaleText">100%</span>
                </div>
            </div>
        </div>
        `;

        // Load konten
        setTimeout(loadContentToIframe, 100);
    });
</script>

<!-- JavaScript Libraries -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<!-- Main JavaScript -->
<script>
    const { jsPDF } = window.jspdf;
    let isContentLoaded = false;

    // Fungsi untuk memuat konten HTML ke dalam iframe
    async function loadContentToIframe() {
        const iframe = document.getElementById('resultPreview');
        const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;

        document.getElementById('loading').style.display = 'block';
        document.getElementById('statusText').textContent = 'Memuat konten...';

        try {
            // Ambil HTML dari server via AJAX
            const response = await fetch(`{{ route('hasil-lab.html-content', $no_lab) }}`);
            const htmlContent = await response.text();

            iframeDoc.open();
            iframeDoc.write(htmlContent);
            iframeDoc.close();

            isContentLoaded = true;

            // Update scale text
            setTimeout(() => {
                updateScaleText();
                adjustIframeSize();
                document.getElementById('loading').style.display = 'none';
                document.getElementById('statusText').textContent = 'Ready';
            }, 500);

        } catch (error) {
            console.error('Error loading content:', error);
            document.getElementById('loading').style.display = 'none';
            document.getElementById('statusText').textContent = 'Error loading content';
        }
    }

    // Fungsi untuk download PDF
    async function downloadPDF() {
        if (!isContentLoaded) {
            alert('Tunggu konten selesai dimuat');
            return;
        }

        document.getElementById('statusText').textContent = 'Menyiapkan PDF...';
        document.getElementById('loading').style.display = 'block';

        try {
            const iframe = document.getElementById('resultPreview');
            const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;

            const pages = iframeDoc.querySelectorAll('.page');

            if (!pages.length) throw new Error('Tidak ada halaman ditemukan');

            const pdf = new jsPDF({
                orientation: 'portrait',
                unit: 'mm',
                format: 'a4',
                compress: true
            });

            for (let i = 0; i < pages.length; i++) {
                const page = pages[i];

                const canvas = await html2canvas(page, {
                    scale: 2,
                    useCORS: true,
                    backgroundColor: '#ffffff',
                    logging: false,
                    width: 210 * 3.78,
                    height: 297 * 3.78,
                    windowWidth: 210 * 3.78,
                    windowHeight: 297 * 3.78,
                    onclone: (clonedDoc, clonedElement) => {
                        clonedElement.style.width = '210mm';
                        clonedElement.style.height = '297mm';
                        clonedElement.style.margin = '0';
                        clonedElement.style.padding = '0';
                        clonedElement.style.background = 'white';

                        // Pastikan gambar cross-origin
                        const images = clonedElement.getElementsByTagName('img');
                        Array.from(images).forEach(img => {
                            if (!img.complete) {
                                img.crossOrigin = 'anonymous';
                            }
                        });
                    }
                });

                const imgData = canvas.toDataURL('image/jpeg', 1.0);
                const imgWidth = 210;
                const imgHeight = (canvas.height * imgWidth) / canvas.width;

                if (i > 0) pdf.addPage();
                pdf.addImage(imgData, 'JPEG', 0, 0, imgWidth, imgHeight, '', 'FAST');

                document.getElementById('statusText').textContent =
                    `Memproses halaman ${i + 1} dari ${pages.length}...`;
            }

            pdf.save(`Hasil-Lab-{{ $no_lab }}-{{ date('Y-m-d') }}.pdf`);
            document.getElementById('statusText').textContent = 'PDF berhasil dibuat';

        } catch (error) {
            console.error('Error:', error);
            document.getElementById('statusText').textContent = 'Error: ' + error.message;
        } finally {
            document.getElementById('loading').style.display = 'none';
        }
    }

    // Fungsi untuk print
    function printResult() {
        if (!isContentLoaded) {
            alert('Tunggu konten selesai dimuat');
            return;
        }

        document.getElementById('statusText').textContent = 'Mempersiapkan print...';

        const iframe = document.getElementById('resultPreview');
        const iframeWindow = iframe.contentWindow || iframe.contentDocument.defaultView;

        setTimeout(() => {
            try {
                iframeWindow.focus();
                iframeWindow.print();
                document.getElementById('statusText').textContent = 'Sedang mencetak...';
            } catch (error) {
                console.error('Print error:', error);
                document.getElementById('statusText').textContent = 'Error print';
            }
        }, 500);
    }

    // Fungsi refresh
    function refreshPreview() {
        document.getElementById('loading').style.display = 'block';
        document.getElementById('statusText').textContent = 'Memuat ulang...';

        setTimeout(() => {
            loadContentToIframe();
        }, 500);
    }

    // Fungsi untuk adjust iframe size
    function adjustIframeSize() {
        const iframeWrapper = document.querySelector('.iframe-wrapper');
        const container = document.querySelector('.preview-container');
        const scaleText = document.getElementById('scaleText');

        if (!iframeWrapper || !container) return;

        const containerWidth = container.clientWidth - 40;
        const containerHeight = container.clientHeight;

        const a4Width = 210;
        const a4Height = 297;

        const widthScale = (containerWidth / a4Width) * 100;
        const heightScale = (containerHeight / a4Height) * 100;

        let scale = Math.min(widthScale, heightScale) / 100;
        scale = Math.min(scale, 1);
        scale = Math.max(scale, 0.3);

        iframeWrapper.style.transform = `scale(${scale})`;

        const scalePercent = Math.round(scale * 100);
        scaleText.textContent = `${scalePercent}%`;
    }

    // Fungsi untuk update scale text
    function updateScaleText() {
        const iframeWrapper = document.querySelector('.iframe-wrapper');
        if (!iframeWrapper) return;

        const transform = window.getComputedStyle(iframeWrapper).transform;

        if (transform === 'none' || transform === 'matrix(1, 0, 0, 1, 0, 0)') {
            document.getElementById('scaleText').textContent = '100%';
        } else {
            const matrix = transform.match(/matrix\(([^)]+)\)/);
            if (matrix) {
                const values = matrix[1].split(', ');
                const scale = parseFloat(values[0]);
                const scalePercent = Math.round(scale * 100);
                document.getElementById('scaleText').textContent = `${scalePercent}%`;
            }
        }
    }

    // Event listeners
    document.addEventListener('DOMContentLoaded', function() {
        window.addEventListener('resize', adjustIframeSize);
        setInterval(updateScaleText, 500);

        // Auto print jika ada parameter
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('print') === 'true') {
            setTimeout(() => {
                if (isContentLoaded) {
                    printResult();
                }
            }, 1500);
        }
    });
</script>
@endsection


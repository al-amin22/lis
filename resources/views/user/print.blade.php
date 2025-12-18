@extends('layouts_user.app')
@section('content')
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header text-center bg-primary text-white">
            <h2 class="mb-0">Hasil Pengujian Laboratorium</h2>
        </div>
        <div class="card-body text-center">
            <div id="loaderContainer">
                <div class="loader mb-3"></div>
                <div class="status" id="statusText">Mohon tunggu, Hasil Laboratorium sedang diproses...</div>
            </div>

            <iframe id="pdfFrame" src="" width="100%" height="700px" style="display:none; border:1px solid #ccc; border-radius:8px;"></iframe>
        </div>
    </div>
</div>

<style>
    .loader {
        border: 6px solid #f3f3f3;
        border-top: 6px solid #3498db;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        margin: 20px auto;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    .status {
        font-size: 1.2rem;
        color: #555;
    }

    .status.ready {
        color: #27ae60;
        font-weight: bold;
    }

    .status.error {
        color: #e74c3c;
        font-weight: bold;
    }
</style>

<script>
    const noLab = "{{ $no_lab }}";
    const iframe = document.getElementById('pdfFrame');
    const loaderContainer = document.getElementById('loaderContainer');
    const statusText = document.getElementById('statusText');

    let attempts = 0;
    const maxAttempts = 40; // hingga 40 x (80 detik total)
    const interval = 2000; // cek tiap 2 detik

    function checkFile() {
        attempts++;

        fetch(`/check-file/${noLab}`)
            .then(res => res.json())
            .then(data => {
                if (data.ready) {
                    loaderContainer.style.display = 'none';
                    iframe.style.display = 'block';
                    iframe.src = data.file; // FILE TERBARU
                    return;
                }

                if (attempts < maxAttempts) {
                    setTimeout(checkFile, interval);
                } else {
                    statusText.textContent = "PDF tidak tersedia.";
                    statusText.classList.add("error");
                }
            })
            .catch(() => {
                if (attempts < maxAttempts) {
                    setTimeout(checkFile, interval);
                } else {
                    statusText.textContent = "Gagal mengecek file.";
                    statusText.classList.add("error");
                }
            });
    }

    checkFile();
</script>


@endsection

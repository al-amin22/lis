@echo off
:: =========================================================
:: Laravel Fast Serve Script - no admin needed
:: =========================================================

echo 🔹 Starting Laravel server at 127.0.0.1:8000...
:: Pastikan path ke php.exe sudah di PATH environment variable
php artisan serve --host=127.0.0.1 --port=8000

pause

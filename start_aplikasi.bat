@echo off
title Menjalankan Ekosistem EAI (SIMAK & Total Billing System)
color 0b

echo ===================================================================
echo     MEMULAI LAYANAN INTEGRASI APLIKASI ENTERPRISE (EAI)
echo ===================================================================
echo.
echo [1/3] Menyiapkan direktori kerja...
set ROOT_DIR=%~dp0

echo [2/3] Menjalankan server lokal:
echo       - SIMAK Portal di http://localhost:8000
echo       - Total Billing System di http://localhost:8001
echo.

:: Menjalankan SIMAK di port 8000
start "SIMAK UNIVERSE (Port 8000)" cmd /k "cd /d "%ROOT_DIR%simak" && color 09 && title SIMAK (Port 8000) && echo Menjalankan SIMAK... && php artisan serve --port=8000"

:: Menjalankan Total Billing System di port 8001
start "Total Billing System (Port 8001)" cmd /k "cd /d "%ROOT_DIR%billing" && color 0a && title Total Billing System (Port 8001) && echo Menjalankan Total Billing System... && php artisan serve --port=8001"

echo [3/3] Menunggu service aktif dalam 3 detik...
timeout /t 3 /nobreak >nul

echo.
echo Membuka browser untuk kedua aplikasi...
start http://localhost:8000
start http://localhost:8001

echo.
echo ===================================================================
echo   KEDUA LAYANAN BERHASIL DIAKTIFKAN!
echo   - SIMAK: http://localhost:8000
echo   - Total Billing System: http://localhost:8001
echo.
echo   Catatan: Pastikan MySQL di Laragon sudah dalam keadaan START.
echo   Jangan tutup jendela terminal server yang baru saja terbuka.
echo ===================================================================
echo.
pause

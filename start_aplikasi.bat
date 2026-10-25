@echo off
chcp 65001 >nul
title Ekosistem EAI - SIMAK dan Total Billing System
color 0b

echo ===================================================================
echo     MEMULAI LAYANAN INTEGRASI APLIKASI ENTERPRISE (EAI)
echo ===================================================================
echo.

set "ROOT_DIR=%~dp0"

:: 1. Memastikan PHP Tersedia dalam PATH
where php >nul 2>&1
if not errorlevel 1 (
    goto :check_mysql
)
if exist "C:\laragon\bin\php\php-8.5.9-nts-Win32-vs17-x64\php.exe" (
    set "PATH=C:\laragon\bin\php\php-8.5.9-nts-Win32-vs17-x64;%PATH%"
) else if exist "C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe" (
    set "PATH=C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64;%PATH%"
) else if exist "%LOCALAPPDATA%\Programs\PHP\current\php.exe" (
    set "PATH=%LOCALAPPDATA%\Programs\PHP\current;%PATH%"
)

:check_mysql
:: 2. Memeriksa Layanan MySQL di Port 3306
echo [1/3] Memeriksa layanan database MySQL di port 3306...
netstat -ano | findstr /C:":3306 " | findstr "LISTENING" >nul 2>&1
if not errorlevel 1 (
    echo       Status: MySQL aktif dan siap menerima koneksi.
) else (
    echo       Status: MySQL belum aktif. Mencoba mengaktifkan mysqld Laragon...
    if exist "C:\laragon\bin\mysql\mysql-8.4.3-winx64\bin\mysqld.exe" (
        start "" /B "C:\laragon\bin\mysql\mysql-8.4.3-winx64\bin\mysqld.exe" --defaults-file="C:\laragon\bin\mysql\mysql-8.4.3-winx64\my.ini" >nul 2>&1
        ping 127.0.0.1 -n 4 >nul
    )
    netstat -ano | findstr /C:":3306 " | findstr "LISTENING" >nul 2>&1
    if not errorlevel 1 (
        echo       Status: MySQL Laragon berhasil diaktifkan.
    ) else (
        echo       [PERHATIAN] MySQL belum aktif. Pastikan Laragon/XAMPP sudah berjalan.
    )
)
echo.

:: 3. Membersihkan Port 8000 dan 8001 jika sedang terpakai
echo [2/3] Menyiapkan port 8000 dan 8001...
for /f "tokens=5" %%p in ('netstat -aon ^| findstr /C:":8000 " ^| findstr "LISTENING"') do taskkill /f /pid %%p >nul 2>&1
for /f "tokens=5" %%p in ('netstat -aon ^| findstr /C:":8001 " ^| findstr "LISTENING"') do taskkill /f /pid %%p >nul 2>&1

:: 4. Menjalankan Server SIMAK dan Total Billing
echo [3/3] Menjalankan server aplikasi Laravel:
echo       - SIMAK Portal di http://127.0.0.1:8000
echo       - Total Billing System di http://127.0.0.1:8001
echo.

start "SIMAK Portal (Port 8000)" /D "%ROOT_DIR%simak" cmd /k "color 09 && title SIMAK (Port 8000) && echo Server SIMAK aktif di http://127.0.0.1:8000... && php artisan serve --host=0.0.0.0 --port=8000"

start "Total Billing System (Port 8001)" /D "%ROOT_DIR%billing" cmd /k "color 0a && title Total Billing (Port 8001) && echo Server Total Billing aktif di http://127.0.0.1:8001... && php artisan serve --host=0.0.0.0 --port=8001"

ping 127.0.0.1 -n 4 >nul

echo Membuka browser pada kedua aplikasi...
start http://127.0.0.1:8000
start http://127.0.0.1:8001

echo.
echo ===================================================================
echo   KEDUA LAYANAN BERHASIL DIAKTIFKAN
echo   - SIMAK Portal        : http://127.0.0.1:8000
echo   - Total Billing System: http://127.0.0.1:8001
echo.
echo   Catatan: Jendela terminal server jangan ditutup selama pengujian.
echo   Untuk mematikan kedua aplikasi, jalankan stop_aplikasi.bat.
echo ===================================================================
echo.
pause

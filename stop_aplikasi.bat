@echo off
chcp 65001 >nul
title Menghentikan Layanan EAI (Port 8000 dan 8001)
color 0c

echo ===================================================================
echo     MENGHENTIKAN LAYANAN EAI (SIMAK DAN TOTAL BILLING SYSTEM)
echo ===================================================================
echo.
echo Mencari dan menghentikan proses pada port 8000 dan 8001...

:: Menghentikan proses pada port 8000 (SIMAK)
for /f "tokens=5" %%a in ('netstat -aon ^| findstr /C:":8000 " ^| findstr "LISTENING"') do (
    echo Menghentikan server SIMAK (PID: %%a)...
    taskkill /f /pid %%a >nul 2>&1
)

:: Menghentikan proses pada port 8001 (Billing)
for /f "tokens=5" %%a in ('netstat -aon ^| findstr /C:":8001 " ^| findstr "LISTENING"') do (
    echo Menghentikan server Total Billing System (PID: %%a)...
    taskkill /f /pid %%a >nul 2>&1
)

echo.
echo Layanan pada port 8000 dan 8001 telah berhasil dihentikan.
echo ===================================================================
echo.
ping 127.0.0.1 -n 2 >nul

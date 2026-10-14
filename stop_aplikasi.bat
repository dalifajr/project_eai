@echo off
title Menghentikan Layanan EAI (Port 8000 & 8001)
color 0c

echo ===================================================================
echo     MENGHENTIKAN LAYANAN EAI (SIMAK & TOTAL BILLING SYSTEM)
echo ===================================================================
echo.
echo Mencari dan menghentikan proses pada port 8000 dan 8001...

:: Menghentikan proses pada port 8000 (SIMAK)
for /f "tokens=5" %%a in ('netstat -aon ^| findstr :8000 ^| findstr LISTENING') do (
    echo Menghentikan SIMAK (PID: %%a)...
    taskkill /f /pid %%a >nul 2>&1
)

:: Menghentikan proses pada port 8001 (Billing)
for /f "tokens=5" %%a in ('netstat -aon ^| findstr :8001 ^| findstr LISTENING') do (
    echo Menghentikan Total Billing System (PID: %%a)...
    taskkill /f /pid %%a >nul 2>&1
)

echo.
echo Layanan pada port 8000 dan 8001 telah berhasil dihentikan.
echo ===================================================================
echo.
pause

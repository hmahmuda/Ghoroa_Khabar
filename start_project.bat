@echo off
echo ========================================
echo    Ghoroa Khabar Project Starter
echo ========================================
echo.

echo Starting XAMPP...
start "" "C:\xampp\xampp-control.exe"

echo.
echo Waiting for XAMPP to start...
timeout /t 5 /nobreak > nul

echo.
echo Opening VS Code...
code .

echo.
echo Opening project in browser...
start "" "http://localhost/Ghoroa_khabar_project/"

echo.
echo ========================================
echo    Project is starting up!
echo ========================================
echo.
echo Please ensure:
echo 1. XAMPP Apache and MySQL are running
echo 2. Database is set up (run setup_database.php)
echo 3. Delivery system is set up (run setup_delivery_system.php)
echo.
echo Default Admin Login:
echo Email: admin@ghoroa.com
echo Password: admin123
echo.
pause 
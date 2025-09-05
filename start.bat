REM CV Builder Egypt - Quick Start Script
@echo off
cls
echo ========================================
echo CV Builder Egypt - Quick Start
echo ========================================
echo.

cd /d "c:\xampp\htdocs\cv-builder"

echo 1. Fixing database...
php artisan app:fix-database

echo.
echo 2. Setting up admin access...
php artisan admin:setup cv_builder@gmail.com

echo.
echo 3. Clearing cache...
php artisan cache:clear
php artisan config:clear
php artisan view:clear

echo.
echo 4. Starting server...
echo ========================================
echo Application will be available at:
echo http://localhost:8000
echo.
echo Login with:
echo Email: test@test.com
echo Password: password
echo.
echo Admin Panel (admins only):
echo http://localhost:8000/admin
echo Admin Email: cv_builder@gmail.com
echo ========================================
echo.
echo Press Ctrl+C to stop the server
echo.

php artisan serve

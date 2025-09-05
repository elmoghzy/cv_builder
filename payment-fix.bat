@echo off
cls
echo ========================================
echo CV Builder Egypt - Payment Fix
echo ========================================
echo.

cd /d "c:\xampp\htdocs\cv-builder"

echo 1. Clearing cache...
php artisan cache:clear
php artisan route:clear
php artisan view:clear

echo.
echo 2. Checking routes...
php artisan route:list --name=payment

echo.
echo ========================================
echo Payment Fix Completed!
echo ========================================
echo.
echo The CV creation and preview should work now!
echo The payment button should work without errors.
echo.
echo Test URLs:
echo 1. Dashboard: http://localhost:8000/dashboard
echo 2. Test CV: http://localhost:8000/test-cv-create
echo.
echo Starting server...
echo ========================================
php artisan serve

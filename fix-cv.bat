@echo off
cls
echo ========================================
echo CV Builder Egypt - CV Creation Fix
echo ========================================
echo.

cd /d "c:\xampp\htdocs\cv-builder"

echo 1. Running database migrations...
php artisan migrate --force

echo.
echo 2. Running quick fix...
php artisan app:quick-fix

echo.
echo 3. Clearing all caches...
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

echo.
echo 4. Setting up admin...
php artisan admin:setup cv_builder@gmail.com

echo.
echo ========================================
echo CV Creation Fix Completed!
echo ========================================
echo.
echo Test URLs:
echo 1. Debug Page: http://localhost:8000/debug-cv
echo 2. CV Builder: http://localhost:8000/cv/builder
echo 3. Login Page: http://localhost:8000/login
echo.
echo Login with:
echo Email: test@test.com
echo Password: password
echo.
echo Starting server...
echo ========================================
php artisan serve

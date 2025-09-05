@echo off
cd /d "c:\xampp\htdocs\cv-builder"

echo ========================================
echo CV Builder Egypt - Admin Setup
echo ========================================
echo.

echo Setting up admin role and permissions...
php artisan admin:setup cv_builder@gmail.com

echo.
echo ========================================
echo Admin setup completed!
echo ========================================
echo.
echo Now only users with admin role can access:
echo http://localhost:8000/admin
echo.
echo Current admin: cv_builder@gmail.com
echo.
pause

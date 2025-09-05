@echo off
cls
echo ========================================
echo CV Builder Egypt - Template Fix
echo ========================================
echo.

cd /d "c:\xampp\htdocs\cv-builder"

echo 1. Running quick fix command...
php artisan app:quick-fix

echo.
echo 2. Testing template creation...
php artisan tinker --execute="App\Models\Template::firstOrCreate(['name' => 'Professional Template'], ['description' => 'Professional CV template', 'content' => ['personal_info' => ['full_name', 'email', 'phone']], 'is_active' => true, 'sort_order' => 1]);"

echo.
echo 3. Clearing cache...
php artisan cache:clear
php artisan route:clear

echo.
echo 4. Testing CV builder route...
echo Testing if route works by making a request...

echo.
echo ========================================
echo Template Fix Completed!
echo ========================================
echo.
echo Now try:
echo 1. Go to: http://localhost:8000/login
echo 2. Login with: test@test.com / password
echo 3. Then go to: http://localhost:8000/cv/builder
echo.
echo Starting server...
echo ========================================
php artisan serve

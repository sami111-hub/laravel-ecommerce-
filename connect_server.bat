@echo off
echo Connecting to production server...
ssh smStore@13.37.138.216
echo.
echo After connecting, run these commands:
echo cd laravel_ecommerce_starte
echo git status
echo git merge --abort
echo git pull origin main
echo php artisan migrate --force
echo php artisan cache:clear
echo php artisan view:clear
echo php artisan config:clear
pause
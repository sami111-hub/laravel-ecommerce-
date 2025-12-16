#!/bin/bash
cd ~/laravel_ecommerce_starte
git remote set-url origin https://github.com/YassenAlmaqtary/laravel_ecommerce_starte.git
git fetch origin
git reset --hard origin/main
php artisan migrate --force
php artisan cache:clear
php artisan view:clear
php artisan config:clear

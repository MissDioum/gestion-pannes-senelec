#!/bin/bash
set -e

echo "Nettoyage et mise en cache de la config Laravel..."
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Execution des migrations..."
php artisan migrate --force

echo "Demarrage d'Apache..."
apache2-foreground

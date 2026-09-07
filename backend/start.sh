#!/bin/bash
set -e

echo "Installing dependencies..."
composer install --no-dev

echo "Running migrations..."
php artisan migrate --force

echo "Starting application..."
php artisan serve --host=0.0.0.0 --port=${PORT:-8000}

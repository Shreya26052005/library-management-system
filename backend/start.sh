#!/bin/bash
set -e

echo "Installing dependencies..."
composer install --no-dev

echo "Waiting for database to be ready..."
for i in {1..30}; do
  if php artisan migrate --force 2>/dev/null; then
    echo "Migrations completed successfully!"
    break
  else
    echo "Attempt $i/30: Database not ready yet, waiting..."
    sleep 2
  fi
done

echo "Starting application..."
php artisan serve --host=0.0.0.0 --port=${PORT:-8000}

#!/bin/bash
set -e

echo "Generating .env from environment variables..."
cat > .env << EOF
APP_NAME="Library Management System"
APP_ENV=${APP_ENV:-production}
APP_DEBUG=${APP_DEBUG:-false}
APP_URL=${APP_URL:-http://localhost:8000}
APP_KEY=${APP_KEY:-base64:O2PJ9fZ5qXkL8mN3pR4sT7uV9w0xYaB2cD4eF6gH8jK0lM2nO3pQ4rS6tU7vW8xYa==}

LOG_LEVEL=${LOG_LEVEL:-debug}

DB_CONNECTION=mysql
DB_HOST=${MYSQLHOST:-localhost}
DB_PORT=${MYSQLPORT:-3306}
DB_DATABASE=${MYSQLDATABASE:-railway}
DB_USERNAME=${MYSQLUSER:-root}
DB_PASSWORD=${MYSQLPASSWORD:-}

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_DRIVER=sync

SANCTUM_STATEFUL_DOMAINS=${SANCTUM_STATEFUL_DOMAINS:-localhost}
EOF

echo ".env generated:"
cat .env

echo ""
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

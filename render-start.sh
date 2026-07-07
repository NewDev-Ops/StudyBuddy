#!/usr/bin/env bash
set -e

# Generate app key if not set (Render env vars can set APP_KEY)
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

# Cache Laravel config, routes, and views for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations (idempotent — safe to run every deploy)
php artisan migrate --force --isolated

# Create storage symlink (idempotent — skips if exists)
php artisan storage:link --force 2>/dev/null || true

# Seed default admin accounts (idempotent — uses updateOrCreate)
php artisan db:seed --class=Database\\Seeders\\AdminSeeder --force

# Start the PHP built-in server
php artisan serve --host=0.0.0.0 --port="${PORT:-10000}"

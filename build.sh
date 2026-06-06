#!/bin/bash
set -e

echo "=== Building Laravel Application for Render ==="

# Install PHP dependencies
echo "Installing Composer dependencies..."
composer install --optimize-autoloader --no-dev

# Install Node dependencies
echo "Installing NPM dependencies..."
npm install

# Build frontend assets
echo "Building frontend assets..."
npm run build

# Generate application key if not set
echo "Setting up environment..."
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

# Cache configuration
echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
echo "Running database migrations..."
php artisan migrate --force

echo "=== Build complete ==="

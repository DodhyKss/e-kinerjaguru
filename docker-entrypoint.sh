#!/bin/bash
set -e

# Copy .env.example to .env if .env doesn't exist
if [ ! -f .env ]; then
    echo "Creating .env from .env.example..."
    cp .env.example .env
fi

# Install PHP dependencies
if [ ! -f vendor/autoload.php ]; then
    echo "Running composer install..."
    composer install --no-interaction
fi

# Install Node.js dependencies
if [ ! -d node_modules ]; then
    echo "Running npm install..."
    npm install
fi

# Build frontend assets if public/build does not exist
if [ ! -d public/build ]; then
    echo "Running npm run build..."
    npm run build
fi

# Generate Laravel App Key if not set
if ! grep -q "^APP_KEY=base64:" .env; then
    echo "Generating app key..."
    php artisan key:generate --no-interaction
fi

# Ensure storage and bootstrap/cache directories are writable
echo "Setting permissions for storage and cache..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Run database migrations
echo "Running migrations..."
php artisan migrate --force || echo "Migration failed (database not ready/accessible), continuing anyway..."

# Start the main process (Apache)
exec "$@"

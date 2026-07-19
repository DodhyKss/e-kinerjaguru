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


# Wait for database and run migrations
echo "Waiting for database to be ready and running migrations..."
max_tries=15
count=0
while [ $count -lt $max_tries ]; do
    if php artisan migrate --force; then
        echo "Migrations completed successfully."
        break
    fi
    echo "Database not ready yet. Retrying in 3 seconds..."
    sleep 3
    count=$((count+1))
done

if [ $count -eq $max_tries ]; then
    echo "Warning: Migrations did not complete successfully after multiple attempts."
fi

# Start the main process (Apache)
exec "$@"

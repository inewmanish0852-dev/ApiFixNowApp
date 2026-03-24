#!/bin/sh

echo "🚀 Starting Laravel App on Render..."

# Clear cache
php artisan config:clear
php artisan cache:clear

# Generate JWT key if not exists
php artisan jwt:secret --force

# Run safe migrations (no data loss)
php artisan migrate --force

# Run seeder only once (if users table empty)
USER_COUNT=$(php artisan tinker --execute="echo DB::table('users')->count();")

if [ "$USER_COUNT" = "0" ]; then
    echo "🌱 Running seeders (first time only)..."
    php artisan db:seed --force --no-interaction
else
    echo "⏭️ Seeder skipped (data already exists)"
fi

# Start Laravel server
php -S 0.0.0.0:10000 -t public
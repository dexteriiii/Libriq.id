#!/bin/bash
set -e

# Symlink storage jika belum ada
php artisan storage:link --force || true

# Optimasi cache Laravel
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Jalankan migrasi jika env RUN_MIGRATIONS diset true
if [ "$RUN_MIGRATIONS" = "true" ]; then
    echo "Menjalankan migrasi database..."
    php artisan migrate --force || true
fi

# Jalankan Apache
exec apache2-foreground

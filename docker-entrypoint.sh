#!/bin/bash
set -e

# Render fournit la variable PORT (10000 par défaut)
PORT="${PORT:-80}"
sed -i "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-available/000-default.conf

php artisan package:discover --ansi || true
php artisan config:clear
php artisan migrate --force

# Seeder : à activer UNE SEULE fois (RUN_SEED=true), puis à retirer
if [ "${RUN_SEED}" = "true" ]; then
  php artisan db:seed --force
fi

php artisan config:cache
php artisan route:cache || true

exec apache2-foreground
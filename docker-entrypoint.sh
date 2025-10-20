#!/bin/bash
set -e

# If PORT is provided (Render sets this), update Apache ports and vhost
if [ -n "${PORT}" ]; then
  echo "Using runtime PORT=${PORT}"
  # Update ports.conf Listen directive
  sed -ri "s/Listen [0-9]+/Listen ${PORT}/" /etc/apache2/ports.conf || true
  # Update any VirtualHost that listens on a port (e.g., <VirtualHost *:80>)
  sed -ri "s/<VirtualHost \*:[0-9]+>/<VirtualHost *:${PORT}>/g" /etc/apache2/sites-available/*.conf || true
fi

# Ensure DocumentRoot is set to APACHE_DOCUMENT_ROOT if provided
if [ -n "${APACHE_DOCUMENT_ROOT}" ]; then
  echo "Setting Apache DocumentRoot to ${APACHE_DOCUMENT_ROOT}"
  sed -ri -e "s!/var/www/html!${APACHE_DOCUMENT_ROOT}!g" /etc/apache2/sites-available/*.conf /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf || true
fi

# Run Laravel optimizations on startup if artisan exists
if [ -f /var/www/html/artisan ]; then
  echo "Running Laravel cache:clear and config:cache (may require env variables)"
  # Allow failures silently to not block container start if env is incomplete
  php /var/www/html/artisan key:generate --force || true
  php /var/www/html/artisan migrate --force || true
  php /var/www/html/artisan cache:clear || true
  php /var/www/html/artisan config:cache || true
fi

# Finally exec the CMD (e.g., apache2-foreground)
exec "$@"

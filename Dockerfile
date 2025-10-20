# Dùng base image chính thức của PHP với Apache
FROM php:8.2-apache

# Install system deps, PHP extensions, Composer and Node (for building assets)
RUN apt-get update && \
    apt-get install -y ca-certificates gnupg lsb-release git curl unzip build-essential && \
    # Install lib dependencies for PHP extensions
    apt-get install -y libzip-dev libicu-dev && \
    docker-php-ext-install pdo_mysql opcache intl zip && \
    # Install Node.js 18 from NodeSource (needed for npm / vite builds)
    curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs && \
    # Clean up apt caches
    rm -rf /var/lib/apt/lists/*

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Enable Apache rewrite and allow .htaccess
RUN a2enmod rewrite && \
    sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf && \
    echo "ServerName localhost" >> /etc/apache2/apache2.conf

WORKDIR /var/www/html

# Copy composer files first to leverage Docker layer cache
COPY composer.json composer.lock ./
# Install PHP dependencies (no-dev for production). Keep --no-interaction for CI builds.
RUN composer install --no-dev --optimize-autoloader --prefer-dist --no-interaction

# Copy package files and build frontend assets if present
COPY package.json package-lock.json* ./
RUN if [ -f package.json ]; then npm ci && npm run build; fi

# Copy the rest of application code
COPY . .

# Set Apache document root to Laravel public directory via env
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
# The actual Listen port will be configured at container start using the entrypoint script

# Ensure correct permissions for storage and cache
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache || true

# Expose a port (Render sets $PORT; 10000 is commonly used but the container will adapt at runtime)
EXPOSE 10000

# Add a small entrypoint which will replace Apache Listen/VHost ports with $PORT at runtime
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
CMD ["apache2-foreground"]
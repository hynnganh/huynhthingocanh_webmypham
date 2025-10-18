FROM php:8.2-apache

RUN apt-get update && \
    apt-get install -y libzip-dev libicu-dev git curl unzip && \
    docker-php-ext-install pdo_mysql opcache intl zip && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN a2enmod rewrite && \
    sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

WORKDIR /var/www/html

COPY .env .env

RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/apache2.conf

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --prefer-dist --no-scripts

COPY . .

RUN mkdir -p /var/www/html/storage /var/www/html/bootstrap/cache && \
    chown -R www-data:www-data /var/www/html && \
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

ENV PORT=10000
EXPOSE 10000

CMD ["sh", "-c", "\
    sed -i \"s/Listen 80/Listen ${PORT}/\" /etc/apache2/ports.conf && \
    php artisan key:generate --force || true && \
    php artisan config:cache --env=production || true && \
    php artisan route:cache --env=production || true && \
    php artisan view:cache --env=production || true && \
    apache2-foreground \
"]

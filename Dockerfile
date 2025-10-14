# Dùng PHP + Apache
FROM php:8.2-apache

# Cài Composer
RUN apt-get update && apt-get install -y zip unzip git curl && \
    curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer

# Bật module rewrite cho Laravel route
RUN a2enmod rewrite

# Đặt thư mục làm việc
WORKDIR /var/www/html

# Copy toàn bộ code dự án vào server
COPY . .

# Cài các thư viện của Laravel
RUN composer install --no-dev --optimize-autoloader --no-scripts --prefer-dist


# Chuyển root Apache tới thư mục public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Mở cổng web
EXPOSE 80

# Chạy server
CMD ["apache2-foreground"]

# Sử dụng PHP 8.2 kèm Apache
FROM php:8.2-apache

# Cài đặt extension và thư viện cần thiết cho Laravel
RUN apt-get update && apt-get install -y \
    libzip-dev libicu-dev git curl unzip \
    && docker-php-ext-install pdo_mysql zip intl opcache \
    && rm -rf /var/lib/apt/lists/*

# Cài Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Bật mod_rewrite cho Laravel routes
RUN a2enmod rewrite

# Thiết lập thư mục làm việc
WORKDIR /var/www/html

# Copy toàn bộ mã nguồn vào container
COPY . .

# Cài đặt dependencies của Laravel
RUN composer install --no-dev --optimize-autoloader --prefer-dist

# Thiết lập Apache Document Root trỏ về /public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf
# Cấp quyền ghi cho storage và cache và đảm bảo www-data là chủ sở hữu toàn bộ web root
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
# Khai báo port cho Render
ARG PORT=10000
ENV PORT=${PORT}
RUN sed -i "s|<VirtualHost \*:80>|<VirtualHost *:${PORT}>|g" /etc/apache2/sites-available/000-default.conf

# Mở port
EXPOSE ${PORT}

# Chạy Apache
CMD ["apache2-foreground"]

# Dùng image PHP chính thức có sẵn Apache
FROM php:8.2-apache

# Cài đặt extension cần thiết cho Laravel
RUN apt-get update && \
    apt-get install -y git curl libzip-dev libicu-dev && \
    docker-php-ext-install pdo_mysql zip intl && \
    rm -rf /var/lib/apt/lists/*

# Cài composer toàn cục
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Bật module rewrite cho Apache (Laravel cần)
RUN a2enmod rewrite

# ⚙️ Cho phép .htaccess hoạt động
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Đặt thư mục làm việc
WORKDIR /var/www/html

# Copy code vào container
COPY . .

# Cài các dependency của Laravel
RUN composer install --no-dev --optimize-autoloader --prefer-dist

# Thiết lập thư mục public là DocumentRoot
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/000-default.conf

# Sửa quyền cho storage và cache
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html && \
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Đặt ServerName để tránh cảnh báo
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Cho phép Render thay đổi PORT (quan trọng)
ENV PORT=80
EXPOSE 80

# Chạy Apache và đảm bảo Listen đúng port Render cấp
CMD ["sh", "-c", "sed -i \"s/Listen 80/Listen ${PORT}/\" /etc/apache2/ports.conf && apache2-foreground"]

# Dùng base image chính thức của PHP với Apache
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

# ⚙️ Cho phép .htaccess hoạt động (fix lỗi 403 Forbidden)
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Đặt thư mục làm việc
WORKDIR /var/www/html

# Copy toàn bộ code vào container
COPY . .

# Cài các dependency của Laravel
RUN composer install --no-dev --optimize-autoloader --prefer-dist

# Thiết lập thư mục public là DocumentRoot
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

# ✅ Cập nhật DocumentRoot trong tất cả file cấu hình Apache (không chỉ 000-default.conf)
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/000-default.conf \
    /etc/apache2/apache2.conf \
    /etc/apache2/conf-available/*.conf

# Sửa quyền cho storage và cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache && \
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Tránh cảnh báo ServerName
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Cho phép Render thay đổi PORT (Render tự đặt port động)
ENV PORT=80
EXPOSE 80

# Lệnh chạy Apache (tự cập nhật port Render)
CMD ["sh", "-c", "sed -i \"s/Listen 80/Listen ${PORT}/\" /etc/apache2/ports.conf && apache2-foreground"]

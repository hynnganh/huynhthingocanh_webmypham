# Dùng base image chính thức của PHP với Apache
FROM php:8.2-apache

# Cài đặt các dependencies cần thiết (zip, git, curl)
RUN apt-get update && \
    apt-get install -y zip unzip git curl && \
    rm -rf /var/lib/apt/lists/*

# Cài Composer toàn cục
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Bật module rewrite cho Laravel route
RUN a2enmod rewrite

# Đặt thư mục làm việc mặc định
WORKDIR /var/www/html

# Copy toàn bộ code dự án vào
COPY . .

# Cài các thư viện của Laravel (Bỏ --no-scripts để service provider discovery hoạt động)
RUN composer install --no-dev --optimize-autoloader --prefer-dist

# Chuyển root Apache tới thư mục public của Laravel
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# CẤP QUYỀN GHI: Cực kỳ quan trọng cho Laravel
# Apache user/group là www-data
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Cấu hình cache cho Production (tùy chọn)
# RUN php artisan config:cache
# RUN php artisan view:cache

# Mở cổng web mặc định của Apache
EXPOSE 80

# Chạy server
CMD ["apache2-foreground"]
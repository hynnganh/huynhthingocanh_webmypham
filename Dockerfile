# Dùng base image chính thức của PHP với Apache
FROM php:8.2-apache
# CÀI ĐẶT DRIVER MYSQL BỊ THIẾU
# Cần gói libzip-dev, libicu-dev và các gói khác cho các extension nếu cần
RUN apt-get update && \
    apt-get install -y libzip-dev libicu-dev && \
    docker-php-ext-install pdo_mysql opcache intl zip && \
    rm -rf /var/lib/apt/lists/*
# Cài đặt các dependencies cần thiết (zip, git, curl)
# LƯU Ý: Những gói này đã có hoặc đã được cài trong bước trên (apt-get install)
RUN apt-get update && \
    apt-get install -y git curl && \
    rm -rf /var/lib/apt/lists/*
# Cài Composer toàn cục
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
# Bật module rewrite cho Laravel route
RUN a2enmod rewrite
# Đặt thư mục làm việc mặc định
WORKDIR /var/www/html
# Copy toàn bộ code dự án vào
COPY . .
# Cài các thư viện của Laravel
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
ENV PORT=10000
EXPOSE 10000
CMD ["php", "-S", "0.0.0.0:${PORT}", "-t","public"]

# Chạy server
CMD ["apache2-foreground"]

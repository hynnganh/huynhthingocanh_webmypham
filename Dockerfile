# Dùng base image chính thức của PHP với Apache
FROM php:8.2-apache

# Đặt thư mục làm việc mặc định
WORKDIR /var/www/html

# CÀI ĐẶT CÁC GÓI VÀ DRIVER CƠ BẢN
RUN apt-get update && \
    apt-get install -y \
        libzip-dev \
        libicu-dev \
        git \
        curl \
        mariadb-client \
    && \
    docker-php-ext-install pdo_mysql opcache intl zip && \
    rm -rf /var/lib/apt/lists/*

# Cài Composer toàn cục
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Bật module rewrite cho Laravel route
RUN a2enmod rewrite

# Copy toàn bộ code dự án vào
COPY . .

# Cài các thư viện của Laravel
RUN php -d opcache.enable=0 /usr/local/bin/composer install --no-dev --optimize-autoloader --prefer-dist

# CHỈNH SỬA CẤU HÌNH PHP: Tăng bộ nhớ và cho phép SSL cho MySQL
RUN echo "memory_limit = 512M" > /usr/local/etc/php/conf.d/memory.ini
RUN echo "pdo_mysql.default_ssl = require" > /usr/local/etc/php/conf.d/ssl.ini

# Cấu hình Web Root Apache sang thư mục 'public'
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/apache2.conf

# CẤP QUYỀN GHI: Cực kỳ quan trọng cho Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# MỞ CỔNG ỨNG DỤNG
EXPOSE 80

# CHẠY TẤT CẢ CÁC LỆNH KHI KHỞI ĐỘNG (RUN TIME)
# Migration -> Cache -> Apache (Web Server)
# Lệnh này phải được dùng để đảm bảo Migration và Caching được chạy với ENV vars đầy đủ
CMD /bin/sh -c "php artisan migrate --force && php artisan config:cache && php artisan route:cache && php artisan view:cache && apache2-foreground"
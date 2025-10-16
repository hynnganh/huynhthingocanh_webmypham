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
        # Thêm mariadb-client/mysql-client để có thể chạy lệnh mysql nếu cần
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
# TẮT opcache để composer install hoạt động ổn định hơn
RUN php -d opcache.enable=0 /usr/local/bin/composer install --no-dev --optimize-autoloader --prefer-dist

# CHỈNH SỬA CẤU HÌNH PHP: Tăng bộ nhớ và cho phép SSL cho MySQL
# Rất quan trọng để tránh lỗi Out of Memory (500/502) và SSL cho Clever Cloud
RUN echo "memory_limit = 512M" > /usr/local/etc/php/conf.d/memory.ini
RUN echo "pdo_mysql.default_ssl = require" > /usr/local/etc/php/conf.d/ssl.ini

# Cấu hình Web Root Apache sang thư mục 'public'
# Giải quyết lỗi AH01276
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/apache2.conf

# CẤP QUYỀN GHI: Cực kỳ quan trọng cho Laravel
# Apache user/group là www-data
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache


# Mở cổng web mặc định của Apache
EXPOSE 80
# Chạy Migration, sau đó Cache các file cấu hình/route, và cuối cùng khởi động Apache.
CMD /bin/sh -c "php artisan migrate --force && php artisan config:cache && php artisan route:cache && php artisan view:cache && apache2-foreground"
# Chạy server
CMD ["apache2-foreground"]
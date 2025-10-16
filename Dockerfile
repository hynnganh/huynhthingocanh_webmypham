# Sử dụng base image chính thức của PHP với Apache
FROM php:8.2-apache

# Đặt thư mục làm việc mặc định
WORKDIR /var/www/html

# CÀI ĐẶT CÁC GÓI VÀ DRIVER CẦN THIẾT
RUN apt-get update && \
    apt-get install -y \
        libzip-dev \
        libicu-dev \
        git \
        curl \
        mariadb-client \
    && \
    # Cài đặt các extension PHP cần thiết cho Laravel
    docker-php-ext-install pdo_mysql opcache intl zip && \
    rm -rf /var/lib/apt/lists/*

# Cài Composer toàn cục
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Bật module rewrite (cần thiết cho Laravel)
RUN a2enmod rewrite

# Sửa lỗi cảnh báo Apache ServerName
RUN echo "ServerName localhost" >> /etc/apache2/conf-available/servername.conf && \
    a2enconf servername

# Copy toàn bộ code dự án vào
COPY . .

# Cài các thư viện của Laravel (Production mode)
RUN php -d opcache.enable=0 /usr/local/bin/composer install --no-dev --optimize-autoloader --prefer-dist

# CHỈNH SỬA CẤU HÌNH PHP: Tăng bộ nhớ
RUN echo "memory_limit = 512M" > /usr/local/etc/php/conf.d/memory.ini
# Thêm cấu hình SSL cho MySQL nếu cần, dựa trên file .env của bạn (DB_SSL_MODE=DISABLED nên dòng này có thể bỏ qua nếu không cần SSL)
# RUN echo "pdo_mysql.default_ssl = require" > /usr/local/etc/php/conf.d/ssl.ini 

# Cấu hình Web Root Apache sang thư mục 'public'
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# CẤP QUYỀN GHI: Cực kỳ quan trọng cho Laravel (storage và cache)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# MỞ CỔNG ỨNG DỤNG
EXPOSE 80

# CHẠY TẤT CẢ CÁC LỆNH KHI KHỞI ĐỘNG (RUN TIME)
# Migration (có || true để container không crash nếu DB chưa sẵn sàng hoặc migration đã chạy)
# Caching (sau khi sửa lỗi route trùng tên, các lệnh này sẽ thành công)
CMD /bin/sh -c "php artisan migrate --force || true && \
               php artisan config:cache || true && \
               php artisan route:cache || true && \
               php artisan view:cache || true && \
               apache2-foreground"
# Dùng base image chính thức của PHP với Apache
FROM php:8.2-apache

# 🛠️ Bước 1: CÀI ĐẶT EXTENSIONS VÀ THƯ VIỆN CƠ BẢN
RUN apt-get update && \
    apt-get install -y libzip-dev libicu-dev git curl unzip && \
    docker-php-ext-install pdo_mysql opcache intl zip && \
    rm -rf /var/lib/apt/lists/*

# ⚙️ Bước 2: CẤU HÌNH CƠ BẢN CHO APACHE VÀ COMPOSER
# Cài Composer toàn cục
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
# Bật module rewrite và cho phép AllowOverride (cần cho .htaccess của Laravel)
RUN a2enmod rewrite && \
    sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf
# Đặt ServerName để tránh cảnh báo Apache
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# 📦 Bước 3: COPY VÀ CÀI ĐẶT LARAVEL
WORKDIR /var/www/html
# Copy dependencies trước để tận dụng Docker Cache
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --prefer-dist --no-scripts
# Copy toàn bộ code dự án còn lại vào
COPY . .

# 🌐 Bước 4: CẤU HÌNH DOCUMENT ROOT (KHẮC PHỤC LỖI AH01276)
# Thiết lập Document Root trỏ về /public trong cấu hình VirtualHost chính
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf
# Đồng thời thay đổi Directory Block chính trong apache2.conf để cấp quyền đúng cho public
RUN sed -i 's!<Directory /var/www/html>!<Directory /var/www/html/public>!' /etc/apache2/apache2.conf

# CẤP QUYỀN GHI: Đặt chủ sở hữu www-data cho toàn bộ web root và cấp quyền cho storage/cache
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 🚀 Bước 5: KHỞI ĐỘNG VÀ CHẠY
# Đặt biến môi trường PORT và mở port (Render cần cổng này)
ENV PORT=10000
EXPOSE 10000

# Lệnh chạy cuối cùng
CMD ["sh", "-c", \
    # 1. Cấu hình port động cho Apache
    "sed -i \"s/Listen 80/Listen ${PORT}/\" /etc/apache2/ports.conf && \
    # 2. Chạy các lệnh Artisan cần thiết
    php artisan key:generate --force || true && \
    php artisan config:cache --env=production || true && \
    php artisan route:cache --env=production || true && \
    php artisan view:cache --env=production || true && \
    # 3. Chạy Apache ở foreground
    apache2-foreground" \
]
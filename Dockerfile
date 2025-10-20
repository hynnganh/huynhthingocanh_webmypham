# Dùng base image chính thức của PHP với Apache
FROM php:8.2-apache

# 🛠️ Bước 1: CÀI ĐẶT EXTENSIONS VÀ THƯ VIỆN CƠ BẢN (Hợp nhất và tối ưu)
# Cài đặt các gói hệ thống cần thiết (git, curl) và các thư viện PHP
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
# Thêm ServerName để tránh cảnh báo AH00558
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# 📦 Bước 3: COPY VÀ CÀI ĐẶT LARAVEL
WORKDIR /var/www/html
# Copy dependencies trước để tận dụng Docker Cache
COPY composer.json composer.lock ./
# Chạy composer install. Dùng --no-scripts để tránh lỗi trong môi trường build.
RUN composer install --no-dev --optimize-autoloader --prefer-dist --no-scripts
# Copy toàn bộ code dự án còn lại
COPY . .

# 🌐 Bước 4: CẤU HÌNH DOCUMENT ROOT VÀ PORT (KHẮC PHỤC LỖI 403 & CỔNG)
# Thiết lập Document Root trỏ về /public (Khắc phục lỗi "Cannot serve directory")
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
# Thay đổi cổng Apache Listen mặc định (80) thành 10000 (Yêu cầu của Render)
RUN sed -i 's/Listen 80/Listen 10000/' /etc/apache2/ports.conf

# CẤP QUYỀN GHI: Cực kỳ quan trọng cho thư mục storage và cache
# Chown toàn bộ web root cho user Apache (www-data)
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 🚀 Bước 5: KHỞI ĐỘNG VÀ CHẠY
# Đặt cổng cho môi trường deploy
EXPOSE 10000
# Lệnh chạy cuối cùng: Chạy các lệnh cache Laravel và sau đó là Apache
# Điều này giúp tối ưu tốc độ load (giảm load chậm)
CMD ["sh", "-c", \
    "php artisan key:generate --force || true && \
    php artisan config:cache --env=production || true && \
    php artisan route:cache --env=production || true && \
    php artisan view:cache --env=production || true && \
    apache2-foreground" \
]
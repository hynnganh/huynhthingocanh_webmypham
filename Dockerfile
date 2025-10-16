# Dùng base image chính thức của PHP với Apache
FROM php:8.2-apache

# Cài đặt extension cần thiết cho Laravel
RUN apt-get update && \
    apt-get install -y git curl libzip-dev libicu-dev && \
    # Cài đặt extension PHP cần thiết
    docker-php-ext-install pdo_mysql zip intl && \
    # Dọn dẹp cache
    rm -rf /var/lib/apt/lists/*

# Cài composer toàn cục
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Bật module rewrite cho Apache (Laravel cần để xử lý URL)
RUN a2enmod rewrite

# ⚙️ Cho phép .htaccess hoạt động trong thư mục /var/www/
# Việc này đảm bảo các quy tắc rewrite của Laravel được áp dụng
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Đặt thư mục làm việc và Copy code
WORKDIR /var/www/html
COPY . .

# Cài các dependency của Laravel
RUN composer install --no-dev --optimize-autoloader --prefer-dist

# Sửa quyền cho storage và cache (Quan trọng)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache && \
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 🚀 CẤU HÌNH DOCUMENT ROOT TRIỆT ĐỂ (Fix lỗi AH01276/403)

# 1. Sao chép cấu hình Virtual Host mới vào Apache
# Tệp 001-laravel.conf phải tồn tại trong thư mục gốc của dự án
COPY 001-laravel.conf /etc/apache2/sites-available/001-laravel.conf

# 2. Tắt cấu hình mặc định (000-default.conf) đang trỏ sai
RUN a2dissite 000-default.conf

# 3. Kích hoạt cấu hình Laravel mới
RUN a2ensite 001-laravel.conf

# Tránh cảnh báo ServerName (thực hành tốt cho Apache)
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Cho phép Render thay đổi PORT
ENV PORT=80
EXPOSE 80

# Lệnh chạy Apache: Sử dụng sh -c để nội suy biến PORT và chạy Apache ở foreground (PID 1)
CMD ["sh", "-c", "sed -i \"s/Listen 80/Listen ${PORT}/\" /etc/apache2/ports.conf && exec apache2-foreground"]
# Dùng base image chính thức của PHP với Apache
FROM php:8.2-apache

# 🛠️ Cài đặt extensions, thư viện cần thiết, và dọn dẹp trong 1 layer
RUN apt-get update && \
    apt-get install -y libzip-dev libicu-dev git curl unzip && \
    docker-php-ext-install pdo_mysql opcache intl zip && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

# Cài Composer toàn cục
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Bật module rewrite và cho phép AllowOverride (cần cho .htaccess)
RUN a2enmod rewrite && \
    sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# ⚙️ Đặt ServerName để tránh cảnh báo Apache
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Đặt thư mục làm việc mặc định
WORKDIR /var/www/html

# Tối ưu Docker Cache: Copy dependencies trước
COPY composer.json composer.lock ./

# Cài các thư viện của Laravel
RUN composer install --no-dev --optimize-autoloader --prefer-dist

# Copy toàn bộ code dự án còn lại vào
COPY . .

# Chuyển Document Root Apache tới thư mục public của Laravel
# Sử dụng 000-default.conf và apache2.conf (cần thiết)
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/apache2.conf

# CẤP QUYỀN GHI: Thiết lập chủ sở hữu và quyền truy cập cho thư mục storage/cache
# Đảm bảo www-data là chủ sở hữu của toàn bộ thư mục web
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 🌐 Cấu hình Port Động cho Render/Các dịch vụ khác
# Đặt biến môi trường PORT và mở port
ENV PORT=10000
EXPOSE 10000

# Lệnh chạy cuối cùng (Giải quyết xung đột và tích hợp cache + port)
# 1. Chuyển đổi port nghe trong ports.conf (giải pháp linh hoạt hơn sed VirtualHost)
# 2. Cache Laravel (route, config, view)
# 3. Chạy Apache foreground
CMD ["sh", "-c", \
    "sed -i \"s/Listen 80/Listen ${PORT}/\" /etc/apache2/ports.conf && \
    php artisan config:cache --env=production || true && \
    php artisan route:cache --env=production || true && \
    php artisan view:cache --env=production || true && \
    apache2-foreground" \
]
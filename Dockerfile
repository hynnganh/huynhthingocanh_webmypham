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

# ⚙️ Cho phép .htaccess hoạt động (Fix lỗi 403 Forbidden)
# Chú ý: Dòng này cho phép AllowOverride All trong /var/www/
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Đặt thư mục làm việc VÀ Copy code vào /var/www/html
WORKDIR /var/www/html
COPY . .

# Cài các dependency của Laravel
RUN composer install --no-dev --optimize-autoloader --prefer-dist

# Sửa quyền cho storage và cache (Thực hiện SAU khi COPY và composer install)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache && \
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 🚀 CẤU HÌNH DOCUMENT ROOT CHÍNH XÁC CHO LARAVEL (Fix lỗi 403/Directory Index)

# 1. Đặt biến môi trường cho DocumentRoot
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

# 2. Áp dụng DocumentRoot mới vào cấu hình Apache
# Ghi đè file 000-default.conf để chỉ rõ DocumentRoot
# (Cách này sạch hơn và dễ kiểm soát hơn việc dùng sed để thay thế toàn bộ)
RUN echo "<VirtualHost *:80>\n" \
     "  DocumentRoot ${APACHE_DOCUMENT_ROOT}\n" \
     "  <Directory ${APACHE_DOCUMENT_ROOT}>\n" \
     "    Options Indexes FollowSymLinks\n" \
     "    AllowOverride All\n" \
     "    Require all granted\n" \
     "  </Directory>\n" \
     "  ErrorLog \${APACHE_LOG_DIR}/error.log\n" \
     "  CustomLog \${APACHE_LOG_DIR}/access.log combined\n" \
     "</VirtualHost>" > /etc/apache2/sites-available/000-default.conf

# Tránh cảnh báo ServerName và cấu hình Render PORT
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Thiết lập port cho Render (Đây là ENV chuẩn, EXPOSE chỉ là thông báo)
ENV PORT=80
EXPOSE 80

# Lệnh chạy Apache (sử dụng sh -c để nội suy biến PORT của Render)
CMD ["sh", "-c", "sed -i \"s/Listen 80/Listen ${PORT}/\" /etc/apache2/ports.conf && exec apache2-foreground"]
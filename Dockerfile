# Dùng base image chính thức của PHP với Apache
FROM php:8.2-apache

# CÀI ĐẶT DRIVER MYSQL BỊ THIẾU
RUN apt-get update && \
    apt-get install -y libzip-dev libicu-dev && \
    docker-php-ext-install pdo_mysql opcache intl zip && \
    rm -rf /var/lib/apt/lists/*

# Cài đặt các dependencies cần thiết (zip, git, curl)
RUN apt-get update && \
    apt-get install -y git curl && \
    rm -rf /var/lib/apt/lists/*

# Cài Composer toàn cục
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Bật module rewrite cho Laravel route
RUN a2enmod rewrite

# ⚙️ Cho phép Laravel sử dụng .htaccess (fix lỗi Forbidden)
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Đặt thư mục làm việc mặc định
WORKDIR /var/www/html

# Copy toàn bộ code dự án vào
COPY . .

# Cài các thư viện của Laravel
RUN composer install --no-dev --optimize-autoloader --prefer-dist

# Chuyển root Apache tới thư mục public của Laravel
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf \
    /etc/apache2/conf-available/*.conf

# CẤP QUYỀN GHI CHO LARAVEL
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html

# Mở cổng web mặc định của Apache
EXPOSE 80

# Cho phép Render chỉ định port động
ENV PORT=80
RUN sed -i "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf || true
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# LỆNH CHẠY APACHE VỚI PORT RENDER CẤP
CMD ["sh", "-c", "sed -i \"s/Listen 80/Listen ${PORT}/\" /etc/apache2/ports.conf && apache2-foreground"]

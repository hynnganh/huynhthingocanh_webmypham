# --- Base image PHP + Apache ---
FROM php:8.2-apache

# --- Cài đặt các extension cần thiết cho Laravel ---
RUN apt-get update && apt-get install -y \
    git unzip zip curl libpng-dev libjpeg-dev libfreetype6-dev libonig-dev libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql gd mbstring zip exif pcntl bcmath opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# --- Cài Composer từ image chính thức ---
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# --- Thư mục làm việc ---
WORKDIR /var/www/html

# --- Copy toàn bộ mã nguồn Laravel ---
COPY . .

# --- Cài đặt dependencies ---
RUN composer install --no-dev --optimize-autoloader --prefer-dist || true

# --- Cấp quyền ghi cho storage và bootstrap ---
RUN mkdir -p bootstrap/cache storage/framework/{cache,sessions,views} \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# --- Kích hoạt rewrite module của Apache ---
RUN a2enmod rewrite

# --- Chuyển Document Root về thư mục public ---
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/000-default.conf /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# --- Mở cổng ---
EXPOSE 10000

# --- Khi container khởi động (Render runtime), mới cache config và view ---
CMD php artisan config:clear && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:clear && \
    php artisan view:cache && \
    apache2-foreground

FROM php:8.2-fpm

# Установим системные зависимости
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libjpeg-dev libfreetype6-dev zip unzip libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Установим Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Копируем проект
WORKDIR /var/www
COPY . .

# Установка зависимостей
RUN composer install --no-dev --optimize-autoloader

# Права доступа
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]
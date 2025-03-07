FROM php:8.3-fpm

# Встановлюємо необхідні пакети та розширення для Laravel і PostgreSQL
RUN apt-get update --allow-insecure-repositories && apt-get install -y --allow-unauthenticated \
    git \
    zip \
    unzip \
    libpq-dev \
    netcat-openbsd \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# Встановлюємо Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Робоча директорія
WORKDIR /var/www

# Копіюємо файли проєкту
COPY . .

# Встановлюємо залежності Composer
RUN composer install --no-interaction --optimize-autoloader

# Копіюємо та робимо виконуваним entrypoint скрипт
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Вказуємо entrypoint і команду за замовчування для запуску Laravel
ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]

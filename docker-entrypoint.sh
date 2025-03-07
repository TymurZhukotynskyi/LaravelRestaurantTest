#!/bin/bash

# Перевіряємо наявність .env, якщо немає — копіюємо з .env.example
if [ ! -f ".env" ]; then
    echo "Copying .env.example to .env..."
    cp .env.example .env
fi

# Завжди запускаємо composer install перед artisan командами
echo "Installing Composer dependencies..."
composer install --no-interaction || {
    echo "Composer install failed. Check composer.json or network connectivity."
    exit 1
}

# Генеруємо APP_KEY, якщо його немає або він порожній
if ! grep -q "^APP_KEY=[^[:space:]]" .env || grep -q "^APP_KEY=$" .env; then
    echo "Generating APP_KEY..."
    php artisan key:generate --force
fi

# Чекаємо, поки PostgreSQL стане доступним
echo "Waiting for PostgreSQL to be ready..."
while ! nc -z db 5432; do
    sleep 1
done

echo "Running migrations..."
php artisan migrate --force --no-interaction
php artisan db:seed

exec "$@"

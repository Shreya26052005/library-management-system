FROM php:8.1-fpm

RUN apt-get update && apt-get install -y \
    git curl libpng-dev libjpeg-dev libfreetype6-dev \
    libjpeg62-turbo-dev libmcrypt-dev libonig-dev libxml2-dev \
    zip unzip && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure gd \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath xml

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN composer install --no-dev --no-interaction

RUN mkdir -p storage/logs storage/framework/cache storage/framework/sessions storage/framework/views

RUN chmod -R 755 storage bootstrap/cache && chmod +x start.sh

EXPOSE 8000

CMD ["bash", "start.sh"]

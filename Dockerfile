FROM php:8.2-cli
RUN docker-php-ext-install pdo pdo_mysql
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /app
COPY . .
RUN composer install --no-dev --optimize-autoloader
CMD [ "sh", "-c", "php -S 0.0.0.0:$PORT main.php" ]
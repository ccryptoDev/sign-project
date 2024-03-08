# Stage 1: Build PHP application
FROM php:8-fpm AS php_builder

# Install PHP dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install --no-scripts --no-autoloader

# Stage 2: Build frontend assets
FROM node:14 AS node_builder

WORKDIR /var/www/html

COPY package.json package-lock.json ./
RUN npm install

# Copy PHP dependencies from previous stage
COPY --from=php_builder /var/www/html/vendor ./vendor

# Copy Laravel application code
COPY . .

# Build frontend assets (e.g., run npm run dev or npm run prod)
RUN npm run prod

# Stage 3: Final image
FROM php:8-fpm

# Copy PHP dependencies from previous stage
COPY --from=php_builder /var/www/html/vendor ./vendor

# Copy Laravel application code
COPY --from=node_builder /var/www/html/public ./public
COPY --from=php_builder /var/www/html ./

# Expose port
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]

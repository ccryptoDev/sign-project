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

# Set working directory
WORKDIR /var/www/html

# Copy Laravel application code
COPY . .

# Install Composer dependencies
RUN composer install --no-scripts --no-autoloader

# Stage 2: Build frontend assets
FROM node:21 AS node_builder

# Set working directory
WORKDIR /var/www/html

# Copy Laravel application code from previous stage
COPY --from=php_builder /var/www/html .

# Copy Composer dependencies from previous stage
COPY --from=php_builder /var/www/html/vendor ./vendor

# Install npm packages
COPY package.json package-lock.json ./
RUN npm install

# Build frontend assets
RUN npm run prod

# Stage 3: Final image
FROM php:8-fpm

# Set working directory
WORKDIR /var/www/html

# Copy PHP dependencies from previous stage
COPY --from=php_builder /var/www/html/vendor ./vendor

# Copy Laravel application code from previous stage
COPY --from=node_builder /var/www/html .

# Expose port
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]

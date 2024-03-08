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

# Stage 2: Install npm and build frontend assets
FROM node:18 AS node_builder

WORKDIR /var/www/html

COPY package.json package-lock.json ./
RUN npm install

# Copy PHP dependencies from previous stage
FROM php_builder AS php_with_node

WORKDIR /var/www/html

# Copy vendor directory from php_builder stage
COPY --from=php_builder /var/www/html/vendor ./vendor

# Copy npm dependencies and built assets from node_builder stage
COPY --from=node_builder /var/www/html/node_modules ./node_modules

# Copy Laravel application code including public directory
COPY . .

# Expose port
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]

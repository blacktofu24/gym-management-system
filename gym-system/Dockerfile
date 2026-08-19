FROM php:8.2-apache

# Install system dependencies, PostgreSQL drivers, and GD for image processing
RUN apt-get update && apt-get install -y \
    libpq-dev \
    zip \
    unzip \
    git \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql gd

# Enable Apache mod_rewrite for Laravel routing
RUN a2enmod rewrite

# Change Apache document root to Laravel's public folder
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Copy Composer from the official image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy all project files into the Docker container
COPY . .

# Install Laravel packages
RUN composer install --no-dev --optimize-autoloader

# Set proper folder permissions for Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port 80 so Render can route traffic
EXPOSE 80

# Run caching, migrations, and start the web server
CMD php artisan config:cache && php artisan route:cache && php artisan view:cache && php artisan migrate --force && apache2-foreground
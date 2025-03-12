FROM php:8.3.11-apache-bullseye

# Install PostgreSQL PDO extension and other dependencies
RUN apt-get update && apt-get install -y \
    libpq-dev \
    git \
    unzip \
    && docker-php-ext-install \
    pdo \
    pdo_pgsql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set up proper permissions for Composer
RUN mkdir -p /var/www/.composer && \
    chown -R www-data:www-data /var/www/.composer

# Copy custom php.ini
COPY php.ini /usr/local/etc/php/conf.d/custom.ini

# Enable Apache rewrite module
RUN a2enmod rewrite

# Add ServerName directive to suppress warning
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Set document root permissions
RUN chown -R www-data:www-data /var/www/html

# Set working directory
WORKDIR /var/www/html

# Create non-root user (if it doesn't exist in the base image)
RUN useradd -m jr-public || true

# We'll keep the default www-data user for Apache to function properly
# instead of switching to jr-public at the end
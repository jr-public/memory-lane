FROM php:8.3.11-apache-bullseye

# Install PostgreSQL PDO extension and other common extensions
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install \
    pdo \
    pdo_pgsql

# Copy custom php.ini
COPY php.ini /usr/local/etc/php/conf.d/custom.ini

# Enable Apache rewrite module (commonly needed)
RUN a2enmod rewrite

# Add ServerName directive to suppress warning
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Set appropriate permissions for web directory
RUN chown -R www-data:www-data /var/www/html

# Set working directory
WORKDIR /var/www/html

# Create non-root user (if it doesn't exist in the base image)
RUN useradd -m jr-public || true

# Switch to the non-root user AFTER setting up permissions
# Note: This should be the last operation
USER jr-public
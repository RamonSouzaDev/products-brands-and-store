# Use the official PHP 8.4 image with Apache
FROM php:8.4-apache

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    nodejs \
    npm \
    sqlite3 \
    libsqlite3-dev

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip pdo_sqlite

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy existing application directory contents
COPY . /var/www/html

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Install Node.js dependencies and build assets
RUN npm ci && npm run build

# Create basic .env file for production
RUN echo 'APP_NAME=Laravel\n\
APP_ENV=production\n\
APP_KEY=\n\
APP_DEBUG=false\n\
APP_TIMEZONE=UTC\n\
APP_URL=http://localhost\n\
APP_LOCALE=en\n\
APP_FALLBACK_LOCALE=en\n\
APP_FAKER_LOCALE=en_US\n\
BCRYPT_ROUNDS=12\n\
LOG_CHANNEL=stack\n\
LOG_STACK=single\n\
LOG_DEPRECATIONS_CHANNEL=null\n\
LOG_LEVEL=error\n\
DB_CONNECTION=sqlite\n\
DB_DATABASE=database.sqlite\n\
SESSION_DRIVER=file\n\
SESSION_LIFETIME=120\n\
SESSION_ENCRYPT=false\n\
SESSION_PATH=/\n\
SESSION_DOMAIN=null\n\
BROADCAST_CONNECTION=log\n\
FILESYSTEM_DISK=local\n\
QUEUE_CONNECTION=database\n\
CACHE_STORE=file\n\
CACHE_PREFIX=\n\
MAIL_MAILER=log\n\
MAIL_HOST=127.0.0.1\n\
MAIL_PORT=2525\n\
MAIL_USERNAME=null\n\
MAIL_PASSWORD=null\n\
MAIL_ENCRYPTION=null\n\
MAIL_FROM_ADDRESS="hello@example.com"\n\
MAIL_FROM_NAME="${APP_NAME}"\n\
VITE_APP_NAME="${APP_NAME}"' > .env

# Generate application key
RUN php artisan key:generate

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Expose port 80
EXPOSE 80

# Create startup script
RUN echo '#!/bin/bash\n\
# Run migrations\n\
php artisan migrate --force\n\
\n\
# Run seeders\n\
php artisan db:seed --force\n\
\n\
# Start Apache\n\
apache2-foreground' > /usr/local/bin/start.sh \
    && chmod +x /usr/local/bin/start.sh

# Start Apache
CMD ["/usr/local/bin/start.sh"]
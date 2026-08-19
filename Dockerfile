FROM php:8.3-cli

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm \
    && docker-php-ext-install pdo_mysql mbstring bcmath gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Get Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy application files
COPY . .

# Install dependencies and build assets
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs
RUN npm install && npm run build

# Expose container port
EXPOSE 8000

# Start command (Runs migrations, seeds admin user, and serves on Railway $PORT)
CMD sh -c "php artisan config:clear && php artisan route:clear && php artisan migrate --force && php artisan db:seed --class=UserSeeder --force && php artisan serve --host 0.0.0.0 --port \${PORT:-8000}"

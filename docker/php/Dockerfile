# Use the official PHP image from Docker Hub
FROM php:8.5-fpm-alpine3.23

# Set UID and GID build arguments with default values
ARG UID=1000
ARG GID=1000

# Pass UID and GID as environment variables
ENV UID=${UID}
ENV GID=${GID}

# Create working directory
RUN mkdir -p /var/www/html

WORKDIR /var/www/html

# Install composer from the official image
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Copy custom PHP configuration
COPY custom.ini /usr/local/etc/php/conf.d/custom.ini

# MacOS staff group's
# Set permissions for Laravel user
RUN delgroup dialout
RUN addgroup -g ${GID} --system laravel
RUN adduser -G laravel --system -D -s /bin/sh -u ${UID} laravel \
    && chown -R laravel:laravel /var/www/html

RUN sed -i "s/user = www-data/user = laravel/g" /usr/local/etc/php-fpm.d/www.conf
RUN sed -i "s/group = www-data/group = laravel/g" /usr/local/etc/php-fpm.d/www.conf
RUN echo "php_admin_flag[log_errors] = on" >> /usr/local/etc/php-fpm.d/www.conf

# Install system dependencies needed for runtime
RUN apk add --no-cache \
    bash \
    curl \
    git \
    libpng \
    libpq \
    icu-libs \
    libzip \
    libxml2 \
    oniguruma \
    freetype \
    libjpeg-turbo \
    linux-headers \
    unzip \
    zip

# Install install-php-extensions (handles all dependency resolution automatically)
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions

# Install all PHP extensions needed for Laravel 12
RUN install-php-extensions \
    pgsql \
    pdo_pgsql \
    mbstring \
    exif \
    zip \
    soap \
    pcntl \
    bcmath \
    opcache \
    intl \
    gd \
    redis \
    xdebug

# Create log folder for Xdebug
RUN mkdir -p /var/log && chmod 777 /var/log

# Configure Xdebug
COPY xdebug.ini /usr/local/etc/php/conf.d/

# Copy Laravel project files
COPY . .

USER laravel

CMD ["php-fpm", "-y", "/usr/local/etc/php-fpm.conf", "-R"]

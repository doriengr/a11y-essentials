FROM php:8.3-fpm-alpine

# Define build arguments
ARG USER_ID
ARG GROUP_ID
ARG INSTALL_XDEBUG="false"

# Define environment variables
ENV DOCUMENT_ROOT=/var/www/html/public
ENV USER_NAME=vvuser
ENV GROUP_NAME=vvuser
ENV USER_ID=$USER_ID
ENV GROUP_ID=$GROUP_ID
ENV USER_ID=${USER_ID:-1001}
ENV GROUP_ID=${GROUP_ID:-1001}
ENV PS1='\u@\h \W \[\033[1;33m\]\$ \[\033[0m\]'
ENV PHP_OPCACHE_VALIDATE_TIMESTAMPS="0"
ENV PHP_XDEBUG_MODE="off"

# Add group and user based on build arguments
RUN addgroup --gid ${GROUP_ID} ${GROUP_NAME}
RUN adduser --disabled-password --gecos '' --uid ${USER_ID} --ingroup vvuser vvuser

RUN mkdir /tmp/xdebug

# Set user and group of working directory
RUN chown -R vvuser:vvuser /var/www/html
RUN chown -R vvuser:vvuser /tmp

# Set nginx document root
RUN mkdir ${DOCUMENT_ROOT}

# Update and install packages
RUN apk update && apk add \
    bash \
    nginx \
    nginx-mod-http-brotli \
    freetype-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    libpng-dev\
    jpeg \
    libxpm-dev

# Install xdebug
RUN if [ "$INSTALL_XDEBUG" = "true" ]; then \
        apk add php83-pecl-xdebug; \
    fi

# Give root permissions to nginx files
RUN chown -R root:root /var/lib/nginx
RUN chmod -R +rx /var/lib/nginx

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install exif
RUN docker-php-ext-install opcache
RUN docker-php-ext-configure gd --with-freetype --with-webp --with-jpeg && \
    docker-php-ext-install gd

# Set nginx and PHP-FPM user
RUN sed -ri -e "s!user nginx!user ${USER_NAME}!g" /etc/nginx/nginx.conf
RUN sed -ri -e "s!user = www-data!user = ${USER_NAME}!g" /usr/local/etc/php-fpm.d/www.conf
RUN sed -ri -e "s!group = www-data!group = ${GROUP_NAME}!g" /usr/local/etc/php-fpm.d/www.conf

# Manualy expose port 80 for outside access to nginx
EXPOSE 80

# Copy configuration to application container
COPY docker/application/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/application/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Use the default production configuration
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
# zzz-custom-php.ini is loaded last (because of the zzz) and can override any setting.
COPY docker/application/php.ini "$PHP_INI_DIR/conf.d/zzz-custom-php.ini"
# Copy php-fpm configuration
COPY docker/application/php-fpm-www.conf /usr/local/etc/php-fpm.d/zzz-www.conf

# Remove the xdebug.ini file if xdebug is not installed
RUN if [ "$INSTALL_XDEBUG" = "false" ]; then \
        rm /usr/local/etc/php/conf.d/xdebug.ini; \
    fi

# Copy app content
COPY --chown=vvuser:vvuser ./ /var/www/html

# Make scripts executeable
RUN chmod +x /var/www/html/docker/application/entrypoint.sh
RUN chmod +x /var/www/html/docker/application/startup.sh
RUN chmod +x /var/www/html/docker/application/healthcheck.sh

# Start app
CMD ["bash", "/var/www/html/docker/application/startup.sh"]

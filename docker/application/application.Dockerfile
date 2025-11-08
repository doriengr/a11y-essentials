FROM php:8.3-fpm-alpine

# ----------------------------
# Build arguments & environment
# ----------------------------
ARG USER_ID
ARG GROUP_ID
ARG INSTALL_XDEBUG="false"

ENV DOCUMENT_ROOT=/var/www/html/public
ENV USER_NAME=vvuser
ENV GROUP_NAME=vvuser
ENV USER_ID=${USER_ID:-1001}
ENV GROUP_ID=${GROUP_ID:-1001}
ENV PS1='\u@\h \W \[\033[1;33m\]\$ \[\033[0m\]'
ENV PHP_OPCACHE_VALIDATE_TIMESTAMPS="0"
ENV PHP_XDEBUG_MODE="off"
ENV PUPPETEER_EXECUTABLE_PATH=/usr/bin/chromium-browser

# ----------------------------
# Create user & directories
# ----------------------------
RUN addgroup --gid ${GROUP_ID} ${GROUP_NAME} \
    && adduser --disabled-password --gecos '' --uid ${USER_ID} --ingroup ${GROUP_NAME} ${USER_NAME} \
    && mkdir -p /tmp/xdebug ${DOCUMENT_ROOT} \
    && chown -R ${USER_NAME}:${GROUP_NAME} /var/www/html /tmp /var/www/html/public

# ----------------------------
# Install system packages
# ----------------------------
RUN apk update && apk add --no-cache \
    bash \
    nginx \
    nginx-mod-http-brotli \
    freetype-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    libpng-dev \
    jpeg \
    libxpm-dev \
    curl \
    git \
    chromium \
    nss \
    udev \
    dumb-init \
    nodejs \
    npm

# ----------------------------
# Install PHP extensions
# ----------------------------
RUN docker-php-ext-install pdo_mysql exif opcache \
    && docker-php-ext-configure gd --with-freetype --with-webp --with-jpeg \
    && docker-php-ext-install gd

# ----------------------------
# Optionally install Xdebug
# ----------------------------
RUN if [ "$INSTALL_XDEBUG" = "true" ]; then apk add php83-pecl-xdebug; fi

# ----------------------------
# Configure Nginx & PHP-FPM
# ----------------------------
RUN sed -ri -e "s!user nginx!user ${USER_NAME}!g" /etc/nginx/nginx.conf \
    && sed -ri -e "s!user = www-data!user = ${USER_NAME}!g" /usr/local/etc/php-fpm.d/www.conf \
    && sed -ri -e "s!group = www-data!group = ${GROUP_NAME}!g" /usr/local/etc/php-fpm.d/www.conf

# ----------------------------
# Copy configurations
# ----------------------------
COPY docker/application/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/application/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini
RUN if [ "$INSTALL_XDEBUG" = "false" ]; then rm /usr/local/etc/php/conf.d/xdebug.ini; fi
COPY docker/application/php.ini "$PHP_INI_DIR/conf.d/zzz-custom-php.ini"
COPY docker/application/php-fpm-www.conf /usr/local/etc/php-fpm.d/zzz-www.conf

COPY --chown=vvuser:vvuser ./ /var/www/html

COPY node/ /var/www/html/node/
RUN cd /var/www/html/node && npm install

RUN chmod +x /var/www/html/docker/application/*.sh

# ----------------------------
# Expose port & start app
# ----------------------------
EXPOSE 80
CMD ["bash", "/var/www/html/docker/application/startup.sh"]

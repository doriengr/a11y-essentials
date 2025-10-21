# Define base image
FROM php:8.3-cli-alpine

# Define build arguments
ARG USER_ID
ARG GROUP_ID

# Define environment variables
ENV USER_ID=$USER_ID
ENV GROUP_ID=$GROUP_ID
ENV USER_ID=${USER_ID:-1001}
ENV GROUP_ID=${GROUP_ID:-1001}
ENV PS1='\u@\h \W \[\033[1;33m\]\$ \[\033[0m\]'

# Add group and user based on build arguments
RUN addgroup --gid ${GROUP_ID} vvuser
RUN adduser --disabled-password --gecos '' --uid ${USER_ID} --ingroup vvuser vvuser

# Set user and group of working directory
RUN chown -R vvuser:vvuser /var/www/html

# Update and install packages
RUN apk update && apk add \
    bash \
    gnupg \
    libzip-dev \
    unzip \
    zip \
    icu-dev \
    icu-libs \
    freetype-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    libpng-dev\
    jpeg \
    openldap-dev \
    patch \
    git

# Install PHP extensions
RUN docker-php-ext-configure intl
RUN docker-php-ext-configure ldap
RUN docker-php-ext-configure gd --with-freetype --with-webp --with-jpeg
RUN docker-php-ext-install pdo_mysql zip bcmath intl exif gd ldap

# Install Composer manually
COPY --from=composer/composer:latest-bin /composer /usr/bin/composer

# Install Node and NPM
COPY --from=node:22-alpine /usr/local/bin /usr/local/bin
COPY --from=node:22-alpine /usr/local/lib/node_modules /usr/local/lib/node_modules

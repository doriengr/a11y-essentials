FROM php:8.3-cli

# -------------------------------
# Build arguments
# -------------------------------
ARG USER_ID
ARG GROUP_ID

# -------------------------------
# Environment variables
# -------------------------------
ENV USER_NAME=vvuser
ENV GROUP_NAME=vvuser
ENV USER_ID=${USER_ID:-1001}
ENV GROUP_ID=${GROUP_ID:-1001}
ENV PS1='\u@\h \W \[\033[1;33m\]\$ \[\033[0m\]'

# -------------------------------
# Safe user and group creation
# -------------------------------
RUN set -eux; \
    # Check if group exists
    if getent group ${GROUP_ID} >/dev/null; then \
        EXISTING_GROUP=$(getent group ${GROUP_ID} | cut -d: -f1); \
        GROUP_NAME=$EXISTING_GROUP; \
    else \
        addgroup --gid ${GROUP_ID} ${GROUP_NAME}; \
    fi; \
    # Create user if UID doesn't exist
    if ! id -u ${USER_ID} >/dev/null 2>&1; then \
        adduser --disabled-password --gecos '' --uid ${USER_ID} --gid ${GROUP_ID} ${USER_NAME}; \
    fi; \
    # Set ownership immediately
    mkdir -p /var/www/html; \
    chown -R ${USER_NAME}:${GROUP_NAME} /var/www/html

# -------------------------------
# Update and install packages
# -------------------------------
RUN apt-get update && apt-get install -y --no-install-recommends \
    bash \
    gnupg \
    libzip-dev \
    unzip \
    zip \
    libicu-dev \
    libfreetype6-dev \
    libjpeg-dev \
    libwebp-dev \
    libpng-dev \
    libldap2-dev \
    patch \
    git \
    chromium \
    chromium-driver \
    && rm -rf /var/lib/apt/lists/*p

# -------------------------------
# Install PHP extensions
# -------------------------------
RUN docker-php-ext-configure intl \
    && docker-php-ext-configure ldap \
    && docker-php-ext-configure gd --with-freetype --with-webp --with-jpeg \
    && docker-php-ext-install pdo_mysql zip bcmath intl exif gd ldap

# -------------------------------
# Install Composer
# -------------------------------
COPY --from=composer/composer:latest-bin /composer /usr/bin/composer

# -------------------------------
# Install Node and NPM
# -------------------------------
COPY --from=node:22-bullseye /usr/local/bin /usr/local/bin
COPY --from=node:22-bullseye /usr/local/lib/node_modules /usr/local/lib/node_modules

# -------------------------------
# Set working directory
# -------------------------------
WORKDIR /var/www/html

# -------------------------------
# Switch to the created user
# -------------------------------
USER ${USER_NAME}

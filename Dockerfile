FROM php:7.3-apache

# Install dev dependencies
RUN apt-get update --fix-missing && \
    apt-get install -y \
    $PHPIZE_DEPS \
    libcurl4-openssl-dev \
    libmagickwand-dev \
    libtool \
    libxml2-dev

# Install production dependencies
RUN apt-get install -y \
    bash \
    curl \
    g++ \
    gcc \
    git \
    imagemagick \
    libc-dev \
    libpng-dev \
    make \
    mariadb-client \
    openssh-client \
    rsync \
    zlib1g-dev \
    vim \
    libzip-dev \
    libpcre3-dev

# Install PECL and PEAR extensions
RUN pecl channel-update pecl.php.net && \
    pecl install \
    imagick \
    xdebug \
    psr \
    phalcon

# Install and enable php extensions
RUN docker-php-ext-enable \
    imagick \
    xdebug \
    psr \
    phalcon
RUN docker-php-ext-configure zip --with-libzip
RUN docker-php-ext-install \
    curl \
    exif \
    iconv \
    mbstring \
    pdo \
    pdo_mysql \
    pcntl \
    tokenizer \
    xml \
    gd \
    zip \
    bcmath

# Setting up certificate
RUN mkdir /var/server-conf/
WORKDIR /var/server-conf/
COPY ["devops/server.conf", "devops/v3.ext", "devops/self-signed.sh", "/var/server-conf/"]

## Variables
ARG DOMAIN
ARG SSL_SELF_SIGNED
ENV SUBJ_CERT "/C=SE/ST=None/L=NB/O=None/CN=${DOMAIN}"

# Setup apache
RUN bash /var/server-conf/self-signed.sh

# Install composer
# Phalcon DevTools with composer
ENV COMPOSER_HOME /composer
ENV PATH ./vendor/bin:/composer/vendor/bin:$PATH
ENV COMPOSER_ALLOW_SUPERUSER 1
RUN curl -s https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin/ --filename=composer
RUN composer global require phalcon/devtools


# Install PHP_CodeSniffer
RUN composer global require "squizlabs/php_codesniffer=*"

# Setup working directory
WORKDIR /var/www/html
COPY ["src/composer.json", "/var/www/html/"]
RUN composer install